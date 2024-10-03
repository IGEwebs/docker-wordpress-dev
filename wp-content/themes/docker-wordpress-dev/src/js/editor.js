require("../sass/editor.scss");

import { registerBlockStyle, unregisterBlockStyle } from "@wordpress/blocks";
import domReady from "@wordpress/dom-ready";

// registerBlockStyle("core/paragraph", {
//   name: "intro",
//   label: "Intro",
// });

domReady(() => {
  unregisterBlockStyle("core/separator", "dots");
  unregisterBlockStyle("core/separator", "wide");
});
