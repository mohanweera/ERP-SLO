<?php
namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;

class SystemAccessIpRestrictionRepository extends BaseRepository
{
    public function generateIPHash($ip)
    {
        return md5($ip);
    }

    public function validateIP($ip)
    {
        $ipHash = $this->generateIPHash($ip);


    }
}
