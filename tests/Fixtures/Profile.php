<?php

namespace Dillingham\Locality\Tests\Fixtures;

use Dillingham\Locality\HasAddress;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasAddress;
}
