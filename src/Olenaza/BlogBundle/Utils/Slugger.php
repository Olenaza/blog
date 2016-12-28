<?php

namespace Olenaza\BlogBundle\Utils;

class Slugger
{
    /**
     * Generate slug from post title and id.
     *
     * @param $string
     * @param $id
     *
     * @return mixed
     */
    public function slugify($string, $id)
    {
        return preg_replace(
            '/[^a-z0-9]/', '-', strtolower(trim(strip_tags($string.$id)))
        );
    }
}
