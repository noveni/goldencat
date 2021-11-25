
const getTitleAndSubtitle = (string) => {
  const index = string.indexOf('<em>');
  const title = string.slice(0, index);
  const subtitle = string.slice(index);

  return {
    title: title.replace(/(<([^>]+)>)/gi, ""),
    subtitle: subtitle.replace(/(<([^>]+)>)/gi, ""),
  }
}

export const parseTitleAndSubtitle = ( string ) => {
  if (string.includes('</strong><em>')) { 
    return getTitleAndSubtitle(string);
  } else if (string.includes('<strong>') || string.includes('<b>')) { 
    return {
      title: string.replace(/(<([^>]+)>)/gi, ""),
    }
  } else {
    return {
      other: string
    }
  }
}
