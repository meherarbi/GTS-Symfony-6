const purgecss = require("@fullhuman/postcss-purgecss");

module.exports = {
  plugins: [
    purgecss({
      content: [".templates/**/*.html.twig"],
    }),
  ],
};
