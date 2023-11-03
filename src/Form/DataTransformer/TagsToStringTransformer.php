<?php

namespace App\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagsToStringTransformer implements DataTransformerInterface
{
    public function transform(mixed $value)
    {
        $tagsJson = $value;
        if (null === $tagsJson) {
            return null;
        }

        return implode(', ', $tagsJson);
    }

    public function reverseTransform(mixed $value)
    {
        $tagsString = $value;
        if (null === $tagsString) {
            return null;
        }

        $assocTags = json_decode($tagsString, true);

        $tags = [];

        if (is_array($assocTags)) {
            foreach ($assocTags as $tag) {
                if (isset($tag['value'])) {
                    $tags[] = $tag['value'];
                }
            }
        }
        return $tags;
    }
}