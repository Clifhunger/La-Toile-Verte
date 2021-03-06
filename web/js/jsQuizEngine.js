﻿// jsQuizEngine https://github.com/crpietschmann/jsQuizEngine
// Copyright (c) 2015 Chris Pietschmann http://pietschsoft.com
// Licensed under MIT License https://github.com/crpietschmann/jsQuizEngine/blob/master/LICENSE
(function (window, $) {

    function getCurrentQuiz(container) {
        return container.find('.question-pool > .quiz');
    }
    function getAllQuestions(container) {
        return container.find('.question-pool > .quiz .question');
    }
    function getQuestionByIndex(container, index) {
        return container.find('.question-pool > .quiz .question:nth-child(' + index + ')');
    }
    function getNowDateTimeStamp() {
        var dt = new Date();
        return dt.getDate() + '/' + dt.getMonth() + '/' + dt.getFullYear() + ' à ' + dt.getHours() + ':' + (dt.getMinutes() >= 10 ? dt.getMinutes() : '0' + dt.getMinutes());
    }

    var ViewModel = function (elem, options) {
        var self = this;
        self.element = $(elem);
        self.options = $.extend({}, engine.defaultOptions, options);

        self.element.find('.question-pool').load(self.options.quizUrl, function () {
            // quiz loaded into browser from HTML file

            getCurrentQuiz(self.element).find('.answer').each(function (e, i) {
                var elem = $(this),
                    newAnswer = $('<label></label>').addClass('answer').append('<input style="float: none;" type=\'checkbox\'/>').append($('<div></div>').html(elem.html()));
                if (elem.is('[data-correct]')) {
                    newAnswer.attr('data-correct', '1');
                }
                elem.replaceWith(newAnswer);
            });

            self.questionCount(getAllQuestions(self.element).length);
    	    for (var i = 1; i <=  self.questionCount(); i++) {
	        	self.questions.push({ index: i, content: getQuestionByIndex(self.element, i), correct: false});
            }
            self.quizTitle(getCurrentQuiz(self.element).attr('data-title'));
            self.quizSubTitle(getCurrentQuiz(self.element).attr('data-subtitle'));
            self.quizCertified(getCurrentQuiz(self.element).attr('data-certified') == '1');
            if (self.quizCertified()) {
                self.timer(2400000);
            }
        });

        self.quizStarted = ko.observable(false);
        self.quizComplete = ko.observable(false);
        self.quizValidated = ko.observable(false);
        self.quizCertified = ko.observable(false);

        self.quizTitle = ko.observable('');
        self.quizSubTitle = ko.observable('');
        self.questionCount = ko.observable(0);
        self.questions = ko.observableArray([]);

        self.timer = ko.observable(0);
        self.timerText = ko.observable('')

        self.currentQuestionIndex = ko.observable(0);
        self.currentQuestionIndex.subscribe(function (newValue) {
            if (newValue < 1) {
                self.currentQuestionIndex(1);
            } else if(newValue > self.questionCount()) {
                self.currentQuestionIndex(self.questionCount());
            } else {
                getAllQuestions(self.element).hide()
                getQuestionByIndex(self.element, newValue).show();
            }

            if (self.questionCount() !== 0) {
                self.currentProgress(self.currentQuestionIndex() / self.questionCount() * 100);
            }
        });
        self.currentProgress = ko.observable(0);

        self.currentQuestionIsFirst = ko.computed(function () {
            return self.currentQuestionIndex() === 1;
        });
        self.currentQuestionIsLast = ko.computed(function () {
            return self.currentQuestionIndex() === self.questionCount();
        });
        self.currentQuestionHasHint = ko.computed(function () {
            var q = getQuestionByIndex(self.element, self.currentQuestionIndex());
            return (q.find('.hint').length > 0);
        });

        var interval;

        self.startQuiz = function () {
            // reset quiz to start state
            self.currentQuestionIndex(0);
            self.currentQuestionIndex(1);

            self.quizStarted(true);

            window.onbeforeunload = function() {
                return "Si vous rafraîchissez la page vous perderez votre progression";
            }

            if (self.quizCertified()) {
                var minutes = Math.floor((self.timer() % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((self.timer() % (1000 * 60)) / 1000);
                var m = minutes > 9 ? "" + minutes: "0" + minutes;
                var s = seconds > 9 ? "" + seconds: "0" + seconds;
                self.timerText("Temps Restant : " + m + ":" + s);
                interval = setInterval(self.timerStart, 1000);
            }
        }

        self.timerStart = function () {
            var minutes = Math.floor((self.timer() % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((self.timer() % (1000 * 60)) / 1000);
            var m = minutes > 9 ? "" + minutes: "0" + minutes;
            var s = seconds > 9 ? "" + seconds: "0" + seconds;

            self.timerText("Temps Restant : " + m + ":" + s);

            if (self.timer() <= 300000) {
                document.getElementById("timer").className = "badge badge-pill badge-danger";
              }

            if (self.timer() < 0) {
              clearInterval(interval);
              alert('Le quiz est terminé');
              self.quizValidated(true);
              self.calculateScore();
            }
            else {
                self.timer(self.timer() - 1000);
            }
        }

        self.moveNextQuestion = function () {
            self.currentQuestionIndex(self.currentQuestionIndex() + 1);
        };
        self.movePreviousQuestion = function () {
            self.currentQuestionIndex(self.currentQuestionIndex() - 1);
        };
        self.showCurrentQuestionHint = function () {
            var q = getQuestionByIndex(self.element, self.currentQuestionIndex());
            q.find('.hint').slideDown();
        };
        self.showCurrentQuestionAnswer = function () {
            var q = getQuestionByIndex(self.element, self.currentQuestionIndex());
            q.find('.answer[data-correct]').addClass('highlight');
            q.find('.description').slideDown();
        };

        self.validateResults = function () {
            var code = document.getElementById('sessionId').value;
            var showResults = window.confirm('Vous ne pourrez plus modifier vos questions');
            if (showResults) {
                self.quizValidated(true);
                self.calculateScore();
                $.ajax({
                    type: "POST",
                    url: code + '/finishQuiz',
                    data: { date: self.calculatedScoreDate(), percent : self.calculatedScore()},
                    success: function(data, dataType)
                    {
                        alert("Vos réponses ont bien été prises en compte");
                    },
        
                    error: function(XMLHttpRequest, textStatus, errorThrown)
                    {
                        alert('Une erreur est survenue: ' + errorThrown);
                    }
                }); 
            }
        }

        self.calculateScore = function () {
            window.onbeforeunload = null;
            clearInterval(interval);
            var correctQuestions = [];
            getAllQuestions(self.element).each(function (i, e) {
                var q = $(this);
                if (q.find('.answer').length === (
                    q.find('.answer[data-correct] > input[type=checkbox]:checked').length + q.find('.answer:not([data-correct]) > input[type=checkbox]:not(:checked)').length
                    )) {
                    correctQuestions.push(q);
                    self.questions()[i] = { index: self.questions()[i].index, content: self.questions()[i].content, correct: true};
                }
            });
    	    self.questions.valueHasMutated(); // required for updating the view

            self.totalQuestionsCorrect(correctQuestions.length);
            if (self.questionCount() !== 0) {
                self.calculatedScore( Math.round( (self.totalQuestionsCorrect() / self.questionCount() * 100) * 10 ) / 10 );
            }
            self.calculatedScoreDate(getNowDateTimeStamp());
        };

        self.getResults = function () {
            self.quizComplete(true);
            $( ".solutions").addClass( "question-pool" ); // needed for styling of questions
            var i = 1;
            $( ".question-pool" ).find('.question').each( function() {
                var q = $(this).clone();
                q.appendTo('#solution-'+ i++);
                q.show();
                q.find('input').each( function() { $(this).attr('disabled','disabled'); });
                q.find('.answer[data-correct]').addClass('highlight');
                q.find('.hint').slideDown();
                q.find('.description').slideDown();
            });
        }

        self.totalQuestionsCorrect = ko.observable(0);
        self.calculatedScore = ko.observable(0);
        self.calculatedScoreDate = ko.observable('');
        self.quizPassed = ko.computed(function () {
            return self.calculatedScore() >= 50;
        });
    };


    var engine = window.jsQuizEngine = function (elem, options) {
        return new engine.fn.init(elem, options);
    };
    engine.defaultOptions = {
        quizUrl: 'original.htm'
    };
    engine.fn = engine.prototype = {
        version: 0.1,
        init: function (elem, options) {
            var vm = new ViewModel(elem[0], options);
            ko.applyBindings(vm, elem[0]);
        }
    };
    engine.fn.init.prototype = engine.fn;


})(window, jQuery);
