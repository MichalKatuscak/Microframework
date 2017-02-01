<?php

namespace Katuscak\Component;

final class Annotation
{
    private $annotation_resolve = [];

    public function __construct(Authorization $authorization)
    {
        $this->annotation_resolve = [
            "Granted" => [$authorization, "isGranted"]
        ];
    }

    public function applyAnnotations(array $annotations)
    {
        $annotations = $this->parseAnnotations($annotations);
        foreach ($annotations as $annotation_name => $annotation_value) {
            if (!empty($this->annotation_resolve[$annotation_name]) && count($this->annotation_resolve[$annotation_name]) == 2) {
                $annotation_class = $this->annotation_resolve[$annotation_name][0];
                $annotation_name = $this->annotation_resolve[$annotation_name][1];

                $annotation_class->$annotation_name($annotation_value);
            }
        }
    }

    private function parseAnnotations(array $annotations): array
    {
        $parsed_annotations = [];
        foreach ($annotations as $annotation) {
            $annotation = trim($annotation);

            $tmp = explode(":", $annotation);
            if (count($tmp) != 2) continue;

            $annotation_name = trim($tmp[0]);
            $annotation_value = trim($tmp[1]);

            $parsed_annotations[$annotation_name] = $annotation_value;
        }

        return $parsed_annotations;
    }
}