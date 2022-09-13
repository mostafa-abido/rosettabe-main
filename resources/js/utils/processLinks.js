import fileIs from './fileIs';

export default function (html, opts = {}) {
  if (!html) return null;
  let res = null;

  res = html.replace(
    new RegExp(`<img src="(?<filename>.*?)" .*?>`),
    (match, fullMatch, filenameMatch, index, rawString, groups) => {
      return `<img src="${fullMatch}" style="max-width: 100%; max-height: inherit; margin: 0 auto;"/>`;
    }
  );

  res = res.replace(
    new RegExp(
      /(https?:\/\/rosetta-app-storage.s3.us-east-2.amazonaws.com\/.*\/(?<filename>.*\.\w+))/
    ),
    (match, fullMatch, filename) => {
      if (fileIs(filename, 'video')) {
        return `<video ${opts?.controls ? 'controls=""' : null}
              class="w-full h-full" style="max-height: inherit;">
              <source src="${match}" type="video/webm" />
              <source src="${match}" type="video/mp4" />
              <source src="${match}" type="video/ogg" />
              Your browser does not support the video tag.
            </video>`;
      } else if (fileIs(filename, 'image')) {
        return `<img src="${match}" style="max-width: 100%; max-height: inherit; margin: 0 auto;"/>`;
      }
      return res;
    }
  );

  return res;
}
