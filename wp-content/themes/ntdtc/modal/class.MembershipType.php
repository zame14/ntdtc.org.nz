<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 5/18/2023
 * Time: 9:23 AM
 */
class MembershipType extends ntdtcBase
{
    public function getFee()
    {
        // get todays date
        date_default_timezone_set('Pacific/Auckland');
        $now = date('Y-m-d');

        // get full payment cut off date
        $cut_off_date = date('Y') . '-07-1';
        $fee = $this->getCustomField('fee1');
        if(strtotime($now) >= strtotime($cut_off_date)) {
            $fee = $this->getCustomField('fee2');
        }
        return $fee;
    }
    public function getCustomField($field)
    {
        return $this->getPostMeta($field);
    }
}