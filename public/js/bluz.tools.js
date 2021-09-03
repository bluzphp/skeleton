/**
 * Small Tools
 *
 * @author   Anton Shevchuk
 * @created  27.12.13 12:21
 */
export {tools};

let tools = {};

/**
 * Prepare string for URL alias
 * @param {String} string input text
 * @return {String} normalized text
 */
tools.alias = string => {
    let str = tools.transliterate(string);
    str = str.toLowerCase();
    str = str.replace(/[ _.:;]+/gi, '-');
    str = str.replace(/[-]+/gi, '-');
    str = str.replace(/[^a-z0-9-]/gi, '');
    return str;
};

/**
 * Transliterate string
 * @param {String} string to transliterate
 * @return {String} transliterated text
 */
tools.transliterate = string => {
    // rules
    let letters;
    let rule;
    letters = {
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
         À  Á  Â  Ã  Ä  Å  Æ  Ā  Ă  Ą  Ç  Ć  Ĉ  Ċ  Č  Ð  Ď  Đ  È  É  Ê  Ë  Ē  Ė  Ę  Ě  Ə
         à  á  â  ã  ä  å  æ  ā  ă  ą  ç  ć  ĉ  ċ  č  ð  ď  đ  è  é  ê  ë  ē  ė  ę  ě  ə

         Ĝ  Ğ  Ġ  Ģ  Ĥ  Ħ  Ì  Í  Î  Ï  Ī  Į  İ  I  Ĳ  Ĵ  Ķ  Ļ  Ł  Ñ  Ń  Ņ  Ň
         ĝ  ğ  ġ  ģ  ĥ  ħ  ì  í  î  ï  ī  į  i  ı  ĳ  ĵ  ķ  ļ  ł  ñ  ń  ņ  ň

         Ò  Ó  Ô  Õ  Ö  Ø  Ő  Œ  Ơ  Ŕ  Ř   Ś  Ŝ  Ş  Ș  Š  Þ  Ţ  Ť  Ù  Ú  Û  Ü  Ū  Ŭ  Ů  Ű  Ų  Ư  Ŵ  Ý  Ŷ  Ÿ  Ź  Ż  Ž
         ò  ó  ô  õ  ö  ø  ő  œ  ơ  ŕ  ř  ß  ś  ŝ  ş  ș  š  þ  ţ  ť  ù  ú  û  ü  ū  ŭ  ů  ű  ų  ư  ŵ  ý  ŷ  ÿ  ź  ż  ž
         */
    };

    // create regular expression rule
    rule = '';
    for (let k in letters) {
        if (letters.hasOwnProperty(k)) {
            rule += k;
        }
    }
    // apply regular expression for replace letters
    rule = new RegExp('[' + rule + ']', 'g');
    return string.replace(rule, (a) => (a in letters) ? letters[a] : '');
};
