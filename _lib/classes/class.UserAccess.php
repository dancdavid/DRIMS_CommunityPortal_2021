<?php

//TODO Build proper UserAccess matrix
class UserAccess{

    public static function ManageLevel2()
    {
        if ($_SESSION['cp_user_level'] == '2')
            return true;
        else
            return false;
    }

    public static function ManageLevel1()
    {
        if ($_SESSION['cp_user_level'] == '2' || $_SESSION['cp_user_level'] == '1')
            return true;
        else
            return false;
    }

    public static function ManageMyOrg($agencyIdDecoded)
    {
        if ($_SESSION['user_type'] == 'ADMIN'
            && $_SESSION['agency_id'] == $agencyIdDecoded
            && $_SESSION['parent_agency'] != $agencyIdDecoded)
            return true;
        else
            return false;
    }


}
