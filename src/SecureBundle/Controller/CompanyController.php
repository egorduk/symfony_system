<?php

namespace SecureBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CompanyController extends Controller
{
    /**
     * @Template()
     *
     * @param int $companyId
     *
     * @return array
     */
    public function infoAction($companyId)
    {
        $company = $this->get('secure.repository.company')->find($companyId);

        return compact('company');
    }
}
