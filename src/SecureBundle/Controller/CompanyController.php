<?php

namespace SecureBundle\Controller;

use SecureBundle\Repository\CompanyRepository;
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
    public function infoAction($companyId = 0)
    {
        $company = $this->get(CompanyRepository::class)->find($companyId);

        return compact('company');
    }
}
