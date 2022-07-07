export default {
  asImageTag(url, alt = 'Картинка', count = 1) {
    let width = '100%';
    if (count % 2 === 0) {
      width = '45%';
    }
    if (count !== 1 && (count % 2 > 0 || count % 3 === 0)) {
      width = '30%';
    }

    return `<img src="${url}" alt="${alt}" style="width: ${width}; margin: 0 5px;"/>`;
  },

  string: {
    ru_chars: {
      а: 'a', б: 'b', в: 'v', г: 'g',
      д: 'd', е: 'e', ё: 'yo', ж: 'zh',
      з: 'z', и: 'i', й: 'i', к: 'k',
      л: 'l', м: 'm', н: 'n', о: 'o',
      п: 'p', р: 'r', с: 's', т: 't',
      у: 'u', ф: 'f', х: 'h', ц: 'c',
      ч: 'ch', ш: 'sh', щ: 'sch',
      ы: 'y', э: 'e', ю: 'yu', я: 'ya',
      0: 0,
    },
    random(length = 16) {
      return Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, length);
    },
    slug(str) {
      const target = str.trim().replace(/\s+/g, '-').toLowerCase();
      const str_contain = target.split('');
      let str_result = '';
      const eng_chars = 'abcdefghigklmnopqrstuvwxyz'.split('');
      str_contain.forEach((char) => {
        if (this.ru_chars.hasOwnProperty(char)) {
          str_result += this.ru_chars[char];
        } else if (eng_chars.includes(char)) {
          str_result += char;
        } else if (char === '-') {
          str_result += char;
        } else if (parseInt(char)) {
          str_result += char;
        }
      });

      return str_result;
    },
  },
};
