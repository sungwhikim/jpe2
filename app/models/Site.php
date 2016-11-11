<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Site
 *
 * Field usage -
 *  title: Used for the <title> tag and where the full name of the site is needed.
 *
 *  name: The plain name of the site used for emails and other places where the title would be too long.
 *
 *  handle: This is used to find the site assets.  There is a subdirectory in image for each site and each site has a
 *          specific css named after the handle.  The handle should be a single word with no spaces or special characters.
 *
 *  domain:  The domain used for this site without any slashes or www.  ex - wms.jpent.com
 *
 *  admin_email_name: The display name of the email address where the site emails are sent from.
 *
 * @package App\Models
 */
class Site extends Model
{
    use SoftDeletes;

    protected $table = 'site';
}
