function endsWithAny(suffixes, string) {
  return suffixes.some(function (suffix) {
    return string.endsWith(suffix);
  });
}

function fileIs(file, type) {
  if (typeof file === 'string') {
    switch (type) {
      case 'image':
        return endsWithAny(
          ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'webp'],
          file.toLowerCase()
        );
      case 'video':
        return endsWithAny(['mp4', 'mov', 'webm', 'qt'], file.toLowerCase());
      case 'text':
        return endsWithAny(
          ['txt', 'url', 'pdf', 'docx', 'xlsx', 'pptx'],
          file.toLowerCase()
        );
      case 'zip':
        return endsWithAny(
          ['zip', 'gzip', 'tar', 'tar.gz'],
          file.toLowerCase()
        );
      default:
        return false;
    }
  } else {
    if (file.type !== 'folder' && file.extension) {
      switch (type) {
        case 'image':
          return endsWithAny(
            ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'webp'],
            file.extension.toLowerCase()
          );
        case 'video':
          return endsWithAny(
            ['mp4', 'mov', 'webm', 'qt'],
            file.extension.toLowerCase()
          );
        case 'text':
          return endsWithAny(
            ['txt', 'url', 'pdf', 'docx', 'xlsx', 'pptx'],
            file.extension.toLowerCase()
          );
        case 'zip':
          return endsWithAny(
            ['zip', 'gzip', 'tar', 'tar.gz'],
            file.extension.toLowerCase()
          );
        default:
          return false;
      }
    } else {
      return file.type.includes(type);
    }
  }
}

export default fileIs;
