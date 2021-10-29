<?php


namespace Infrastructure\Traits;

trait prepareOrganizationDataTrait
{
    private function prepareOrganization(array &$data)
    {
        $this->prepareOrganizationLogo($data);
    }

    private function prepareOrganizationLogo(array &$data)
    {
        if (!isset($data['logo_file_relation'])) {
            $data['logo'] = asset('images/default-organization.png');
        } else {
            $data['logo'] = asset($data['logo_file_relation']['address']);
        }

        unset($data['logo_file_relation']);
        unset($data['logo_file_id']);
    }
}
