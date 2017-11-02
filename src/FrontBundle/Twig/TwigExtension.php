<?php

namespace FrontBundle\Twig;


class TwigExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'delete' => new \Twig_SimpleFilter('deleteFile', array($this, 'deleteFile')),
        );
    }

    public function deleteFile($filename) {
        unlink($filename);
        return null;
    }
}
