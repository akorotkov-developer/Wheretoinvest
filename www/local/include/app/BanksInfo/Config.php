<?
namespace Wic\BanksInfo;

class Config {
    const CLIENT = "http://cbr.ru/CreditInfoWebServ/CreditOrgInfo.asmx?WSDL";
    const WEBSITES = "http://www.cbr.ru/credit/GetAsXML.asp";
    const BANKS = "http://cbr.ru/scripts/XML_bic2.asp";

    const HMATRIX = 9;
    const HOFFRES = 3;

    const ENDDATEOFFER = "12.10.2019";

    const ALL_REGIONS = array(71,77,24,34,3,16,18,32,23,17,83,66,4,64,38,26,5,78,42,67,47,6,31,65,85,60,7,20,8,79,19,2,25,29,48,28,68,69,54,9,53,52,81,27,35,75,55,72,37,41,36,22,21,46,44,80,39,45,74,73,33,10,51,30,57,82,59,4913,11,40,12,13,70,14,58,49,56,76,62,63,43,50,84,61,15);

    const OFFER_UF_METHOD = 3;
    const OFFER_UF_TYPE = 26;

    const MATRIX_UF_SUMM = 10000;
    const MATRIX_UF_DATE_START = 369;
    const MATRIX_UF_CURRENCY = 28;
    const MATRIX_UF_PERCENT = 0.1;

    const EMAIL_END = "@wheretoinvest.ru";
    const PASSWORD_KEY = ".VbV943050";

    const PARTENRS_GROUP = array(3, 4, 6);

    const CLIENT_NAME = "Богдан";
}