<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 5/19/2023
 * Time: 10:46 AM
 */
class PetClass extends ntdtcBase
{
    public function getCustomField($field)
    {
        return $this->getPostMeta($field);
    }
    public function getFeatureImage()
    {
        return get_the_post_thumbnail($this->Post, 'full');
    }
    public function getClassTimes()
    {
        return wpautop($this->getPostMeta('class-times'));
    }
    public function getClassFee()
    {
        return wpautop($this->getPostMeta('class-fee'));
    }
}