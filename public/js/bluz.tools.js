/**
 * Small Tools
 *
 * @author   Anton Shevchuk
 * @created  27.12.13 12:21
 */
/*global define,require*/
define(['jquery', 'bluz'], function ($, bluz) {
    "use strict";
    let tools = {};

    /**
     * Prepare string for URL alias
     * @param string
     */
    tools.alias = function(string) {
        string = tools.transliterate(string);
        string = string.toLowerCase();
        string = string.replace(/[ _.:;]+/gi, "-");
        string = string.replace(/[-]+/gi, "-");
        string = string.replace(/[^a-z0-9-]/gi, "");
        return string;
    };
    /**
     * Transliterate string
     * @param string
     */
    tools.transliterate = function(string) {
        // rules
        let L = {
                // Cyrillic RU, UA, BE
                'А': 'A', 'а': 'a',
                'Б': 'B', 'б': 'b',
                'В': 'V', 'в': 'v',
                'Г': 'G', 'г': 'g',
                'Ґ': 'G', 'ґ': 'g',
                'Д': 'D', 'д': 'd',
                'Е': 'E', 'е': 'e',
                'Ё': 'Yo', 'ё': 'yo',
                'Є': 'Ye', 'є': 'ye',
                'Ж': 'Zh', 'ж': 'zh',
                'З': 'Z', 'з': 'z',
                'И': 'I', 'и': 'i',
                'І': 'I', 'і': 'i',
                'Ї': 'Ji', 'ї': 'ji',
                'Й': 'Y', 'й': 'y',
                'К': 'K', 'к': 'k',
                'Л': 'L', 'л': 'l',
                'М': 'M', 'м': 'm',
                'Н': 'N', 'н': 'n',
                'О': 'O', 'о': 'o',
                'П': 'P', 'п': 'p',
                'Р': 'R', 'р': 'r',
                'С': 'S', 'с': 's',
                'Т': 'T', 'т': 't',
                'У': 'U', 'у': 'u',
                'Ў': 'U', 'ў': 'u',
                'Ф': 'F', 'ф': 'f',
                'Х': 'Kh', 'х': 'kh',
                'Ц': 'Ts', 'ц': 'ts',
                'Ч': 'Ch', 'ч': 'ch',
                'Ш': 'Sh', 'ш': 'sh',
                'Щ': 'Sch', 'щ': 'sch',
                'Ъ': '', 'ъ': '',
                'Ы': 'Y', 'ы': 'y',
                'Ь': '', 'ь': '',
                'Э': 'E', 'э': 'e',
                'Ю': 'Yu', 'ю': 'yu',
                'Я': 'Ya', 'я': 'ya'
                // Latin
                // TODO:
                /*
                 À	Á	Â	Ã	Ä	Å	Æ	Ā	Ă	Ą	Ç	Ć	Ĉ	Ċ	Č	Ð	Ď	Đ	È	É	Ê	Ë	Ē	Ė	Ę	Ě	Ə
                 à	á	â	ã	ä	å	æ	ā	ă	ą	ç	ć	ĉ	ċ	č	ð	ď	đ	è	é	ê	ë	ē	ė	ę	ě	ə

                 Ĝ	Ğ	Ġ	Ģ	Ĥ	Ħ	Ì	Í	Î	Ï	Ī	Į	İ	I	Ĳ	Ĵ	Ķ	Ļ	Ł	Ñ	Ń	Ņ	Ň
                 ĝ	ğ	ġ	ģ	ĥ	ħ	ì	í	î	ï	ī	į	i	ı	ĳ	ĵ	ķ	ļ	ł	ñ	ń	ņ	ň

                 Ò	Ó	Ô	Õ	Ö	Ø	Ő	Œ	Ơ	Ŕ	Ř	 	Ś	Ŝ	Ş	Ș	Š	Þ	Ţ	Ť	Ù	Ú	Û	Ü	Ū	Ŭ	Ů	Ű	Ų	Ư	Ŵ	Ý	Ŷ	Ÿ	Ź	Ż	Ž
                 ò	ó	ô	õ	ö	ø	ő	œ	ơ	ŕ	ř	ß	ś	ŝ	ş	ș	š	þ	ţ	ť	ù	ú	û	ü	ū	ŭ	ů	ű	ų	ư	ŵ	ý	ŷ	ÿ	ź	ż	ž
                 */
            },
            r = '',
            k;
        for (k in L) {
            if (L.hasOwnProperty(k)) {
                r += k;
            }
        }
        r = new RegExp('[' + r + ']', 'g');
        k = function (a) {
            return a in L ? L[a] : '';
        };

        return string.replace(r, k);
    };

    return tools;
});