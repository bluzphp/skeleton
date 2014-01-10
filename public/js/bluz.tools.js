/**
 * Small Tools
 *
 * @author   Anton Shevchuk
 * @created  27.12.13 12:21
 */
/*global define,require*/
define(['jquery', 'bluz'], function ($, bluz) {
    "use strict";
    var tools = {};

    /**
     * Prepare string for URL alias
     * @param string
     */
    tools.alias = function(string) {
        string = tools.transliterate(string);
        string = string.toLowerCase();
        string = string.replace(/[ _:;]+/gi, "-");
        string = string.replace(/[-]+/gi, "-");
        string = string.replace(/[^a-z0-9.-]/gi, "");
        return string;
    };
    /**
     * Transliterate string
     * @param string
     */
    tools.transliterate = function(string) {
        // rules
        var L = {
                'А': 'A', 'а': 'a', 'Б': 'B', 'б': 'b', 'В': 'V', 'в': 'v', 'Г': 'G', 'г': 'g',
                'Д': 'D', 'д': 'd', 'Е': 'E', 'е': 'e', 'Ё': 'Yo', 'ё': 'yo', 'Ж': 'Zh', 'ж': 'zh',
                'З': 'Z', 'з': 'z', 'И': 'I', 'и': 'i', 'Й': 'Y', 'й': 'y', 'К': 'K', 'к': 'k',
                'Л': 'L', 'л': 'l', 'М': 'M', 'м': 'm', 'Н': 'N', 'н': 'n', 'О': 'O', 'о': 'o',
                'П': 'P', 'п': 'p', 'Р': 'R', 'р': 'r', 'С': 'S', 'с': 's', 'Т': 'T', 'т': 't',
                'У': 'U', 'у': 'u', 'Ф': 'F', 'ф': 'f', 'Х': 'Kh', 'х': 'kh', 'Ц': 'Ts', 'ц': 'ts',
                'Ч': 'Ch', 'ч': 'ch', 'Ш': 'Sh', 'ш': 'sh', 'Щ': 'Sch', 'щ': 'sch', 'Ъ': '"', 'ъ': '"',
                'Ы': 'Y', 'ы': 'y', 'Ь': "", 'ь': "", 'Э': 'E', 'э': 'e', 'Ю': 'Yu', 'ю': 'yu',
                'Я': 'Ya', 'я': 'ya'
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