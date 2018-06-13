<?php

namespace AI\ResponsiveBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Intl\Intl;

class registerType extends AbstractType
{
    private $locale;
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $this->locale;
        $builder->add('firstName', 'text', array(
            'required' => true
        ))->add('lastName', 'text', array(
            'required' => true
        ))->add('country', 'choice', array(
            'choices' => array(
                'empty_value' => '',
                'AF_Afghanistan' => Intl::getRegionBundle()->getCountryName("AF", $locale),
                'AL_Albania' => Intl::getRegionBundle()->getCountryName("AL", $locale),
                'DZ_Algeria' => Intl::getRegionBundle()->getCountryName("DZ", $locale),
                'AS_American Samoa' => Intl::getRegionBundle()->getCountryName("AS", $locale),
                'AD_Andorra' => Intl::getRegionBundle()->getCountryName("AD", $locale),
                'AO_Angola' => Intl::getRegionBundle()->getCountryName("AO", $locale),
                'AI_Anguilla' => Intl::getRegionBundle()->getCountryName("AI", $locale),
                'AQ_Antarctica' => Intl::getRegionBundle()->getCountryName("AQ", $locale),
                'AG_Antigua and Barbuda' => Intl::getRegionBundle()->getCountryName("AG", $locale),
                'AR_Argentina' => Intl::getRegionBundle()->getCountryName("AR", $locale),
                'AM_Armenia' => Intl::getRegionBundle()->getCountryName("AM", $locale),
                'AW_Aruba' => Intl::getRegionBundle()->getCountryName("AW", $locale),
                'AU_Australia' => Intl::getRegionBundle()->getCountryName("AU", $locale),
                'AT_Austria' => Intl::getRegionBundle()->getCountryName("AT", $locale),
                'AZ_Azerbaijan' => Intl::getRegionBundle()->getCountryName("AZ", $locale),
                'BS_Bahamas' => Intl::getRegionBundle()->getCountryName("BS", $locale),
                'BH_Bahrain' => Intl::getRegionBundle()->getCountryName("BH", $locale),
                'BD_Bangladesh' => Intl::getRegionBundle()->getCountryName("BD", $locale),
                'BB_Barbados' => Intl::getRegionBundle()->getCountryName("BB", $locale),
                'BY_Belarus' => Intl::getRegionBundle()->getCountryName("BY", $locale),
                'BE_Belgium' => Intl::getRegionBundle()->getCountryName("BE", $locale),
                'BZ_Belize' => Intl::getRegionBundle()->getCountryName("BZ", $locale),
                'BJ_Benin' => Intl::getRegionBundle()->getCountryName("BJ", $locale),
                'BM_Bermuda' => Intl::getRegionBundle()->getCountryName("BM", $locale),
                'BT_Bhutan' => Intl::getRegionBundle()->getCountryName("BT", $locale),
                'BO_Bolivia' => Intl::getRegionBundle()->getCountryName("BO", $locale),
                'BA_Bosnia and Herzegovina' => Intl::getRegionBundle()->getCountryName("BA", $locale),
                'BW_Botswana' => Intl::getRegionBundle()->getCountryName("BW", $locale),
                'BR_Brazil' => Intl::getRegionBundle()->getCountryName("BR", $locale),
                'BQ_British Antarctic Territory' => Intl::getRegionBundle()->getCountryName("BQ", $locale),
                'IO_British Indian Ocean Territory' => Intl::getRegionBundle()->getCountryName("IO", $locale),
                'VG_British Virgin Islands' => Intl::getRegionBundle()->getCountryName("VG", $locale),
                'BN_Brunei' => Intl::getRegionBundle()->getCountryName("BN", $locale),
                'BG_Bulgaria' => Intl::getRegionBundle()->getCountryName("BG", $locale),
                'BF_Burkina Faso' => Intl::getRegionBundle()->getCountryName("BF", $locale),
                'BI_Burundi' => Intl::getRegionBundle()->getCountryName("BI", $locale),
                'KH_Cambodia' => Intl::getRegionBundle()->getCountryName("KH", $locale),
                'CM_Cameroon' => Intl::getRegionBundle()->getCountryName("CM", $locale),
                'CA_Canada' => Intl::getRegionBundle()->getCountryName("CA", $locale),
                'CV_Cape Verde' => Intl::getRegionBundle()->getCountryName("CV", $locale),
                'KY_Cayman Islands' => Intl::getRegionBundle()->getCountryName("KY", $locale),
                'CF_Central African Republic' => Intl::getRegionBundle()->getCountryName("CF", $locale),
                'TD_Chad' => Intl::getRegionBundle()->getCountryName("TD", $locale),
                'CL_Chile' => Intl::getRegionBundle()->getCountryName("CL", $locale),
                'CN_China' => Intl::getRegionBundle()->getCountryName("CN", $locale),
                'CX_Christmas Island' => Intl::getRegionBundle()->getCountryName("CX", $locale),
                'CC_Cocos [Keeling] Islands' => Intl::getRegionBundle()->getCountryName("CC", $locale),
                'CO_Colombia' => Intl::getRegionBundle()->getCountryName("CO", $locale),
                'KM_Comoros' => Intl::getRegionBundle()->getCountryName("KM", $locale),
                'CG_Congo - Brazzaville' => Intl::getRegionBundle()->getCountryName("CG", $locale),
                'CD_Congo - Kinshasa' => Intl::getRegionBundle()->getCountryName("CD", $locale),
                'CK_Cook Islands' => Intl::getRegionBundle()->getCountryName("CK", $locale),
                'CR_Costa Rica' => Intl::getRegionBundle()->getCountryName("CR", $locale),
                'HR_Croatia' => Intl::getRegionBundle()->getCountryName("HR", $locale),
                'CU_Cuba' => Intl::getRegionBundle()->getCountryName("CU", $locale),
                'CY_Cyprus' => Intl::getRegionBundle()->getCountryName("CY", $locale),
                'CZ_Czech Republic' => Intl::getRegionBundle()->getCountryName("CZ", $locale),
                'CI_Côte d’Ivoire' => Intl::getRegionBundle()->getCountryName("CI", $locale),
                'DK_Denmark' => Intl::getRegionBundle()->getCountryName("DK", $locale),
                'DJ_Djibouti' => Intl::getRegionBundle()->getCountryName("DJ", $locale),
                'DM_Dominica' => Intl::getRegionBundle()->getCountryName("DM", $locale),
                'DO_Dominican Republic' => Intl::getRegionBundle()->getCountryName("DO", $locale),
                'EC_Ecuador' => Intl::getRegionBundle()->getCountryName("EC", $locale),
                'EG_Egypt' => Intl::getRegionBundle()->getCountryName("EG", $locale),
                'SV_El Salvador' => Intl::getRegionBundle()->getCountryName("SV", $locale),
                'GQ_Equatorial Guinea' => Intl::getRegionBundle()->getCountryName("GQ", $locale),
                'ER_Eritrea' => Intl::getRegionBundle()->getCountryName("ER", $locale),
                'EE_Estonia' => Intl::getRegionBundle()->getCountryName("EE", $locale),
                'ET_Ethiopia' => Intl::getRegionBundle()->getCountryName("ET", $locale),
                'FK_Falkland Islands' => Intl::getRegionBundle()->getCountryName("FK", $locale),
                'FO_Faroe Islands' => Intl::getRegionBundle()->getCountryName("FO", $locale),
                'FJ_Fiji' => Intl::getRegionBundle()->getCountryName("FJ", $locale),
                'FI_Finland' => Intl::getRegionBundle()->getCountryName("FI", $locale),
                'FR_France' => Intl::getRegionBundle()->getCountryName("FR", $locale),
                'GF_French Guiana' => Intl::getRegionBundle()->getCountryName("GF", $locale),
                'PF_French Polynesia' => Intl::getRegionBundle()->getCountryName("PF", $locale),
                'TF_French Southern Territories' => Intl::getRegionBundle()->getCountryName("TF", $locale),
                'GA_Gabon' => Intl::getRegionBundle()->getCountryName("GA", $locale),
                'GM_Gambia' => Intl::getRegionBundle()->getCountryName("GM", $locale),
                'GE_Georgia' => Intl::getRegionBundle()->getCountryName("GE", $locale),
                'DE_Germany' => Intl::getRegionBundle()->getCountryName("DE", $locale),
                'GH_Ghana' => Intl::getRegionBundle()->getCountryName("GH", $locale),
                'GI_Gibraltar' => Intl::getRegionBundle()->getCountryName("GI", $locale),
                'GR_Greece' => Intl::getRegionBundle()->getCountryName("GR", $locale),
                'GL_Greenland' => Intl::getRegionBundle()->getCountryName("GL", $locale),
                'GD_Grenada' => Intl::getRegionBundle()->getCountryName("GD", $locale),
                'GP_Guadeloupe' => Intl::getRegionBundle()->getCountryName("GP", $locale),
                'GU_Guam' => Intl::getRegionBundle()->getCountryName("GU", $locale),
                'GT_Guatemala' => Intl::getRegionBundle()->getCountryName("GT", $locale),
                'GG_Guernsey' => Intl::getRegionBundle()->getCountryName("GG", $locale),
                'GN_Guinea' => Intl::getRegionBundle()->getCountryName("GN", $locale),
                'GW_Guinea-Bissau' => Intl::getRegionBundle()->getCountryName("GW", $locale),
                'GY_Guyana' => Intl::getRegionBundle()->getCountryName("GY", $locale),
                'HT_Haiti' => Intl::getRegionBundle()->getCountryName("HT", $locale),
                'HN_Honduras' => Intl::getRegionBundle()->getCountryName("HN", $locale),
                'HK_Hong Kong SAR China' => Intl::getRegionBundle()->getCountryName("HK", $locale),
                'HU_Hungary' => Intl::getRegionBundle()->getCountryName("HU", $locale),
                'IS_Iceland' => Intl::getRegionBundle()->getCountryName("IS", $locale),
                'IN_India' => Intl::getRegionBundle()->getCountryName("IN", $locale),
                'ID_Indonesia' => Intl::getRegionBundle()->getCountryName("ID", $locale),
                'IR_Iran' => Intl::getRegionBundle()->getCountryName("IR", $locale),
                'IQ_Iraq' => Intl::getRegionBundle()->getCountryName("IQ", $locale),
                'IE_Ireland' => Intl::getRegionBundle()->getCountryName("IE", $locale),
                'IM_Isle of Man' => Intl::getRegionBundle()->getCountryName("IM", $locale),
                'IL_Israel' => Intl::getRegionBundle()->getCountryName("IL", $locale),
                'IT_Italy' => Intl::getRegionBundle()->getCountryName("IT", $locale),
                'JM_Jamaica' => Intl::getRegionBundle()->getCountryName("JM", $locale),
                'JP_Japan' => Intl::getRegionBundle()->getCountryName("JP", $locale),
                'JE_Jersey' => Intl::getRegionBundle()->getCountryName("JE", $locale),
                'JO_Jordan' => Intl::getRegionBundle()->getCountryName("JO", $locale),
                'KZ_Kazakhstan' => Intl::getRegionBundle()->getCountryName("KZ", $locale),
                'KE_Kenya' => Intl::getRegionBundle()->getCountryName("KE", $locale),
                'KI_Kiribati' => Intl::getRegionBundle()->getCountryName("KI", $locale),
                'KW_Kuwait' => Intl::getRegionBundle()->getCountryName("KW", $locale),
                'KG_Kyrgyzstan' => Intl::getRegionBundle()->getCountryName("KG", $locale),
                'LA_Laos' => Intl::getRegionBundle()->getCountryName("LA", $locale),
                'LV_Latvia' => Intl::getRegionBundle()->getCountryName("LV", $locale),
                'LB_Lebanon' => Intl::getRegionBundle()->getCountryName("LB", $locale),
                'LS_Lesotho' => Intl::getRegionBundle()->getCountryName("LS", $locale),
                'LR_Liberia' => Intl::getRegionBundle()->getCountryName("LR", $locale),
                'LY_Libya' => Intl::getRegionBundle()->getCountryName("LY", $locale),
                'LI_Liechtenstein' => Intl::getRegionBundle()->getCountryName("LI", $locale),
                'LT_Lithuania' => Intl::getRegionBundle()->getCountryName("LT", $locale),
                'LU_Luxembourg' => Intl::getRegionBundle()->getCountryName("LU", $locale),
                'MO_Macau SAR China' => Intl::getRegionBundle()->getCountryName("MO", $locale),
                'MK_Macedonia' => Intl::getRegionBundle()->getCountryName("MK", $locale),
                'MG_Madagascar' => Intl::getRegionBundle()->getCountryName("MG", $locale),
                'MW_Malawi' => Intl::getRegionBundle()->getCountryName("MW", $locale),
                'MY_Malaysia' => Intl::getRegionBundle()->getCountryName("MY", $locale),
                'MV_Maldives' => Intl::getRegionBundle()->getCountryName("MV", $locale),
                'ML_Mali' => Intl::getRegionBundle()->getCountryName("ML", $locale),
                'MT_Malta' => Intl::getRegionBundle()->getCountryName("MT", $locale),
                'MH_Marshall Islands' => Intl::getRegionBundle()->getCountryName("MH", $locale),
                'MQ_Martinique' => Intl::getRegionBundle()->getCountryName("MQ", $locale),
                'MR_Mauritania' => Intl::getRegionBundle()->getCountryName("MR", $locale),
                'MU_Mauritius' => Intl::getRegionBundle()->getCountryName("MU", $locale),
                'YT_Mayotte' => Intl::getRegionBundle()->getCountryName("YT", $locale),
                'MX_Mexico' => Intl::getRegionBundle()->getCountryName("MX", $locale),
                'FM_Micronesia' => Intl::getRegionBundle()->getCountryName("FM", $locale),
                'MD_Moldova' => Intl::getRegionBundle()->getCountryName("MD", $locale),
                'MC_Monaco' => Intl::getRegionBundle()->getCountryName("MC", $locale),
                'MN_Mongolia' => Intl::getRegionBundle()->getCountryName("MN", $locale),
                'ME_Montenegro' => Intl::getRegionBundle()->getCountryName("ME", $locale),
                'MS_Montserrat' => Intl::getRegionBundle()->getCountryName("MS", $locale),
                'MA_Morocco' => Intl::getRegionBundle()->getCountryName("MA", $locale),
                'MZ_Mozambique' => Intl::getRegionBundle()->getCountryName("MZ", $locale),
                'MM_Myanmar [Burma]' => Intl::getRegionBundle()->getCountryName("MM", $locale),
                'NA_Namibia' => Intl::getRegionBundle()->getCountryName("NA", $locale),
                'NR_Nauru' => Intl::getRegionBundle()->getCountryName("NR", $locale),
                'NP_Nepal' => Intl::getRegionBundle()->getCountryName("NP", $locale),
                'NL_Netherlands' => Intl::getRegionBundle()->getCountryName("NL", $locale),
                'NC_New Caledonia' => Intl::getRegionBundle()->getCountryName("NC", $locale),
                'NZ_New Zealand' => Intl::getRegionBundle()->getCountryName("NZ", $locale),
                'NI_Nicaragua' => Intl::getRegionBundle()->getCountryName("NI", $locale),
                'NE_Niger' => Intl::getRegionBundle()->getCountryName("NE", $locale),
                'NG_Nigeria' => Intl::getRegionBundle()->getCountryName("NG", $locale),
                'NU_Niue' => Intl::getRegionBundle()->getCountryName("NU", $locale),
                'NF_Norfolk Island' => Intl::getRegionBundle()->getCountryName("NF", $locale),
                'MP_Northern Mariana Islands' => Intl::getRegionBundle()->getCountryName("MP", $locale),
                'NO_Norway' => Intl::getRegionBundle()->getCountryName("NO", $locale),
                'OM_Oman' => Intl::getRegionBundle()->getCountryName("OM", $locale),
                'PK_Pakistan' => Intl::getRegionBundle()->getCountryName("PK", $locale),
                'PW_Palau' => Intl::getRegionBundle()->getCountryName("PW", $locale),
                'PS_Palestinian Territories' => Intl::getRegionBundle()->getCountryName("PS", $locale),
                'PA_Panama' => Intl::getRegionBundle()->getCountryName("PA", $locale),
                'PG_Papua New Guinea' => Intl::getRegionBundle()->getCountryName("PG", $locale),
                'PY_Paraguay' => Intl::getRegionBundle()->getCountryName("PY", $locale),
                'PE_Peru' => Intl::getRegionBundle()->getCountryName("PE", $locale),
                'PH_Philippines' => Intl::getRegionBundle()->getCountryName("PH", $locale),
                'PN_Pitcairn Islands' => Intl::getRegionBundle()->getCountryName("PN", $locale),
                'PL_Poland' => Intl::getRegionBundle()->getCountryName("PL", $locale),
                'PT_Portugal' => Intl::getRegionBundle()->getCountryName("PT", $locale),
                'PR_Puerto Rico' => Intl::getRegionBundle()->getCountryName("PR", $locale),
                'QA_Qatar' => Intl::getRegionBundle()->getCountryName("QA", $locale),
                'RO_Romania' => Intl::getRegionBundle()->getCountryName("RO", $locale),
                'RU_Russia' => Intl::getRegionBundle()->getCountryName("RU", $locale),
                'RW_Rwanda' => Intl::getRegionBundle()->getCountryName("RW", $locale),
                'RE_Réunion' => Intl::getRegionBundle()->getCountryName("RE", $locale),
                'BL_Saint Barthélemy' => Intl::getRegionBundle()->getCountryName("BL", $locale),
                'SH_Saint Helena' => Intl::getRegionBundle()->getCountryName("SH", $locale),
                'KN_Saint Kitts and Nevis' => Intl::getRegionBundle()->getCountryName("KN", $locale),
                'LC_Saint Lucia' => Intl::getRegionBundle()->getCountryName("LC", $locale),
                'MF_Saint Martin' => Intl::getRegionBundle()->getCountryName("MF", $locale),
                'PM_Saint Pierre and Miquelon' => Intl::getRegionBundle()->getCountryName("PM", $locale),
                'WS_Samoa' => Intl::getRegionBundle()->getCountryName("WS", $locale),
                'SM_San Marino' => Intl::getRegionBundle()->getCountryName("SM", $locale),
                'SA_Saudi Arabia' => Intl::getRegionBundle()->getCountryName("SA", $locale),
                'SN_Senegal' => Intl::getRegionBundle()->getCountryName("SN", $locale),
                'RS_Serbia' => Intl::getRegionBundle()->getCountryName("RS", $locale),
                'SC_Seychelles' => Intl::getRegionBundle()->getCountryName("SC", $locale),
                'SL_Sierra Leone' => Intl::getRegionBundle()->getCountryName("SL", $locale),
                'SG_Singapore' => Intl::getRegionBundle()->getCountryName("SG", $locale),
                'SK_Slovakia' => Intl::getRegionBundle()->getCountryName("SK", $locale),
                'SI_Slovenia' => Intl::getRegionBundle()->getCountryName("SI", $locale),
                'SB_Solomon Islands' => Intl::getRegionBundle()->getCountryName("SB", $locale),
                'SO_Somalia' => Intl::getRegionBundle()->getCountryName("SO", $locale),
                'ZA_South Africa' => Intl::getRegionBundle()->getCountryName("ZA", $locale),
                'GS_South Georgia' => Intl::getRegionBundle()->getCountryName("GS", $locale),
                'KR_South Korea' => Intl::getRegionBundle()->getCountryName("KR", $locale),
                'ES_Spain' => Intl::getRegionBundle()->getCountryName("ES", $locale),
                'LK_Sri Lanka' => Intl::getRegionBundle()->getCountryName("LK", $locale),
                'SD_Sudan' => Intl::getRegionBundle()->getCountryName("SD", $locale),
                'SR_Suriname' => Intl::getRegionBundle()->getCountryName("SR", $locale),
                'SJ_Svalbard and Jan Mayen' => Intl::getRegionBundle()->getCountryName("SJ", $locale),
                'SZ_Swaziland' => Intl::getRegionBundle()->getCountryName("SZ", $locale),
                'SE_Sweden' => Intl::getRegionBundle()->getCountryName("SE", $locale),
                'CH_Switzerland' => Intl::getRegionBundle()->getCountryName("CH", $locale),
                'SY_Syria' => Intl::getRegionBundle()->getCountryName("SY", $locale),
                'ST_São Tomé and Príncipe' => Intl::getRegionBundle()->getCountryName("ST", $locale),
                'TW_Taiwan' => Intl::getRegionBundle()->getCountryName("TW", $locale),
                'TJ_Tajikistan' => Intl::getRegionBundle()->getCountryName("TJ", $locale),
                'TZ_Tanzania' => Intl::getRegionBundle()->getCountryName("TZ", $locale),
                'TH_Thailand' => Intl::getRegionBundle()->getCountryName("TH", $locale),
                'TL_Timor-Leste' => Intl::getRegionBundle()->getCountryName("TL", $locale),
                'TG_Togo' => Intl::getRegionBundle()->getCountryName("TG", $locale),
                'TK_Tokelau' => Intl::getRegionBundle()->getCountryName("TK", $locale),
                'TO_Tonga' => Intl::getRegionBundle()->getCountryName("TO", $locale),
                'TT_Trinidad and Tobago' => Intl::getRegionBundle()->getCountryName("TT", $locale),
                'TN_Tunisia' => Intl::getRegionBundle()->getCountryName("TN", $locale),
                'TR_Turkey' => Intl::getRegionBundle()->getCountryName("TR", $locale),
                'TM_Turkmenistan' => Intl::getRegionBundle()->getCountryName("TM", $locale),
                'TC_Turks and Caicos Islands' => Intl::getRegionBundle()->getCountryName("TC", $locale),
                'TV_Tuvalu' => Intl::getRegionBundle()->getCountryName("TV", $locale),
                'UG_Uganda' => Intl::getRegionBundle()->getCountryName("UG", $locale),
                'UA_Ukraine' => Intl::getRegionBundle()->getCountryName("UA", $locale),
                'AE_United Arab Emirates' => Intl::getRegionBundle()->getCountryName("AE", $locale),
                'GB_United Kingdom' => Intl::getRegionBundle()->getCountryName("GB", $locale),
                'US_United States' => Intl::getRegionBundle()->getCountryName("US", $locale),
                'UY_Uruguay' => Intl::getRegionBundle()->getCountryName("UY", $locale),
                'UZ_Uzbekistan' => Intl::getRegionBundle()->getCountryName("UZ", $locale),
                'VU_Vanuatu' => Intl::getRegionBundle()->getCountryName("VU", $locale),
                'VA_Vatican City' => Intl::getRegionBundle()->getCountryName("VA", $locale),
                'VE_Venezuela' => Intl::getRegionBundle()->getCountryName("VE", $locale),
                'VN_Vietnam' => Intl::getRegionBundle()->getCountryName("VN", $locale),
                'WF_Wallis and Futuna' => Intl::getRegionBundle()->getCountryName("WF", $locale),
                'EH_Western Sahara' => Intl::getRegionBundle()->getCountryName("EH", $locale),
                'YE_Yemen' => Intl::getRegionBundle()->getCountryName("YE", $locale),
                'ZM_Zambia' => Intl::getRegionBundle()->getCountryName("ZM", $locale),
                'ZW_Zimbabwe' => Intl::getRegionBundle()->getCountryName("ZW", $locale),
                'AX_Åland Islands' => Intl::getRegionBundle()->getCountryName("AX", $locale)
            )
        ))->add('industry', 'choice', array(
            'choices' => array(
                '' => '',
                'Bodycare, Fashion & Accessories' => 'Bodycare, Fashion & Accessories',
                'Electrical & Electronic Products' => 'Electrical & Electronic Products',
                'Food & Food Packaging' => 'Food & Food Packaging',
                'Gifts & Premiums' => 'Gifts & Premiums',
                'Homeware & Gardenware' => 'Homeware & Gardenware',
                'Industrial, Construction & Mechanical Items' => 'Industrial, Construction & Mechanical Items',
                'Printing & Packaging' => 'Printing & Packaging',
                'Textile, Apparel, Footwear & Accessories' => 'Textile, Apparel, Footwear & Accessories',
                'Toys & Recreational Items' => 'Toys & Recreational Items'
            )
        ))->add('company', 'text', array(
            'required' => true
        ))->add('telephone', 'text', array(
            'required' => true
        ))->add('email', 'email', array(
            'required' => true
        ))->add('username', 'text', array(
            'required' => true,
            'pattern' => "^[a-zA-Z0-9-@_\.]{1,}$"
        ))->add('password', 'password', array(
            'required' => true,
            'pattern' => "^[^\s]{6,}$"
        ));
    }
    
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AI\ResponsiveBundle\Entity\Register'
        ));
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'ai_responsivebundle_register';
    }
    
}