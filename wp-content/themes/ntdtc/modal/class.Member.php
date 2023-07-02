<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 5/16/2023
 * Time: 10:52 AM
 */
class Member extends ntdtcBase
{
    public function getCustomField($field)
    {
        return $this->getPostMeta($field);
    }
    function getDisplayName()
    {
        return $this->getPostMeta('member-firstname') . ' ' . $this->getPostMeta('member-surname');
    }
    function isEmailAddressUnique()
    {
        $status = '';
        // get user by role - member
        $args = array(
            'role'    => 'member',
            'orderby' => 'ID',
            'order'   => 'ASC'
        );
        $users = get_users( $args );
        if(!empty($users)) {
            foreach ($users as $user) {
                if ($user->user_email == $this->getPostMeta('member-email')) {
                    $status = 'no';
                    break;
                }
            }
        }
        return $status;
    }
    function isPending()
    {
        if($this->getPostMeta('status') == "Pending" || $this->getPostMeta('status') == "Pending renewal")
        {
            return true;
        } else {
            return false;
        }
    }
    public function getMembershipType()
    {
        $id = toolset_get_related_post( $this->id(), 'membership-type', 'parent');
        return new MembershipType($id);
    }
    function getRegistrationDate()
    {
        return date('F j, Y', $this->getPostMeta('membership-start-date'));
    }
    function getRenewDate()
    {
        $html = 'N/A';
        if($this->getPostMeta('date-membership-renewed') <> "") {
            $html = date('F j, Y', $this->getPostMeta('date-membership-renewed'));
        }
        return $html;
    }
    function getStatus()
    {
        $status = $this->getPostMeta('status');
        if($this->hasExpired()) {
            $status = 'Your membership has expired. Please click <a href="' . get_page_link(206) . '">here</a> to renew your membership.';
        }
        return $status;
    }
    function hasExpired()
    {
        $registrationDate = $this->getPostMeta('membership-start-date');
        //if member has renewed use renewal date
        if($this->getPostMeta('date-membership-renewed') <> "") {
            $registrationDate = $this->getPostMeta('date-membership-renewed');
        }
        // get membership year
        $year = date('Y',$registrationDate);
        //$year = '2022';
        //get membership end date
        $dateIn = $year . '-12-31';
        $end_date = strtotime($dateIn);
        if($registrationDate > $end_date) {
            return true;
        } else {
            return false;
        }
    }
    function subscribeUserMailChimp($email)
    {
        $api_key = get_field('mailchimp_api_key',5);
        $list_id = get_field('mailchimp_list_id',5);
        $status = 'subscribed';

        $data = array(
            'apikey' => $api_key,
            'email_address' => $email,
            'status' => $status
        );
        $API_URL = 'https://' . substr($api_key,strpos($api_key,'-') + 1 ) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($data['email_address']));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.base64_encode( 'user:'.$api_key )));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data) );
        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result);

        return $response->status;
    }
}