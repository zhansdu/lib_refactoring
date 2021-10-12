export const option = (
  options: Object | undefined,
  type: string,
  parameter: string
) => {
  if (options) {
    if (options[type]) {
      return (options as Object)[type as string][parameter];
    }
  } else {
    return "";
  }
};
