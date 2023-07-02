<?php

/**
 * Created by PhpStorm..
 * User: user
 * Date: 5/22/2023
 * Time: 8:51 PM
 */
class Event extends ntdtcBase
{
    public function getCustomField($field)
    {
        return $this->getPostMeta($field);
    }
    public function getFeatureImage()
    {
        return get_the_post_thumbnail($this->Post, 'full');
    }
    function getEventDay($format)
    {
        return date($format,$this->getPostMeta('event-start-date'));
    }
    function getEventDate()
    {
        $date = date('F d Y',$this->getPostMeta('event-start-date')) . ' &commat; '  . date('g:i a',$this->getPostMeta('event-start-date'));
        if($this->getPostMeta('event-end-date') <> "") {
            $date .= ' - ' . date('g:i a',$this->getPostMeta('event-end-date'));
        }
        return $date;
    }
    function link()
    {
        $link = 'javascript:;';
        if($this->getPostMeta('event-link') <> "") {
            $link = $this->getPostMeta('event-link');
        }
        return $link;
    }
    function isUpcomingEvent()
    {
        //date_default_timezone_set('Pacific/Auckland');
        $date = date("Y-m-d");// current date
        $now =  strtotime(date("Y-m-d", strtotime($date)));
        if($this->getPostMeta('event-start-date') >= $now) {
            return true;
        } else {
            return false;
        }
    }
}