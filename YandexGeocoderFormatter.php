<?php

namespace common\components;

class YandexGeocoderFormatter
{
    /**
     * @var array
     */
    public $data;
    public $country;
    public $countryCode;
    public $city;
    public $postalCode;
    public $province;
    public $locality;
    public $street;
    public $house;
    public $fullAddress;
    public $district;

    /**
     * YandexGeocoderFormatter constructor.
     *
     * @param string $data - JSON
     */
    public function __construct($data)
    {
        $dataGeoCode = json_decode($data, true);
        if (isset($dataGeoCode['GeoObjectCollection']['featureMember'][0]['GeoObject'])) {
            $this->data = $dataGeoCode['metaDataProperty']['GeocoderMetaData'];
            $this->countryCode = $this->data['Address']['country_code'];
            $this->postalCode = $this->data['Address']['postal_code'];
            $this->fullAddress = $this->data['Address']['formatted'];
            $components = $this->data['Address']['Components'];
            foreach ($components as $component) {
                switch ($component['kind']) {
                    case 'country':
                        $this->country = $component['kind']['name'];
                        break;
                    case 'street':
                        $this->street = $component['kind']['name'];
                        break;
                    case 'locality':
                        $this->city = $component['kind']['name'];
                        break;
                    case 'house':
                        $this->house = $component['kind']['name'];
                        break;
                }
            }
            $dopData = $this->getDataProvince($components);
            $this->district = $dopData[0] ?? null;

        }
    }

    /**
     * @param array $components
     *
     * @return array
     */
    private function getDataProvince($components)
    {
        $items = [];
        foreach ($components as $item) {
            switch ($item['kind']) {
                case 'province':
                    $items[] = $item['kind']['name'];
                    break;
            }
        }

        return $items;
    }
}
