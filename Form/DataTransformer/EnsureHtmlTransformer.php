<?php

namespace Ivory\CKEditorBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class EnsureHtmlTransformer implements DataTransformerInterface
{
    public function transform($content)
    {
        return $content;
    }

    /**
     * If no html tags exist on the content then add them in
     *
     * @param mixed $content
     * @return mixed|null|string
     */
    public function reverseTransform($content)
    {
        if (!$content) {
            return null;
        }

        if ($content == strip_tags($content)) {
            $content = '<p>' . nl2br($content) . '</p>';
        }

        return $content;
    }
}