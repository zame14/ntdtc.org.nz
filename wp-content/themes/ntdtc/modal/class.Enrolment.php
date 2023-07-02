<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 5/19/2023
 * Time: 10:47 AM
 */
class Enrolment extends ntdtcBase
{
    public function getCustomField($field)
    {
        return $this->getPostMeta($field);
    }
    function isPending()
    {
        if($this->getPostMeta('enrolment-status') == "Pending")
        {
            return true;
        } else {
            return false;
        }
    }
    public function getClassID()
    {
        $id = toolset_get_related_post( $this->id(), 'class-enrolment', 'parent');
        $pet_class =  new PetClass($id);
        return $pet_class->id();
    }
}