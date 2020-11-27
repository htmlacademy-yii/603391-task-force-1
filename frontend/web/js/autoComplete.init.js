
    new autoComplete({
data: {
    src: async () => {
    const token = "this_is_the_API_token_number";
    const query = document.querySelector("#autoComplete").value;
    const source = await fetch(
    "/address/location?search="+query
    );
    const data = await source.json();
    return data;
},
    key: ['text'],
    cache: false
},
sort: (a, b) => {                    // Sort rendered results ascendingly | (Optional)
if (a.match < b.match) return -1;
if (a.match > b.match) return 1;
return 0;
},
placeHolder: "",     // Place Holder text                 | (Optional)
selector: "#autoComplete",           // Input field selector              | (Optional)
threshold: 3,                        // Min. Chars length to start Engine | (Optional)
debounce: 1000,                      // Post duration for engine to start | (Optional)
searchEngine: "loose",               // Search Engine type/mode           | (Optional)
resultsList: {                       // Rendered results list object      | (Optional)
    render: true,
    container: source => {
    source.setAttribute("id", "location-list");
    source.setAttribute("class", "input");
},
    destination: document.querySelector("#autoComplete"),
    position: "afterend",
    element: "ul"
},
maxResults: 5,                         // Max. number of rendered results | (Optional)
highlight: true,                       // Highlight matching results      | (Optional)
resultItem: {                          // Rendered result item            | (Optional)
    content: (data, source) => {
    source.innerHTML = data.match;
    source.setAttribute("class", "input");
},
    element: "li"
},
noResults: () => {
    document.querySelector("#lat").value = '';
    document.querySelector("#lng").value = '';
    document.querySelector("#city").value = '';
},
onSelection: (feedback) => {
    console.log(feedback.selection.value);
    document.querySelector("#autoComplete").value = feedback.selection.value.text;
    document.querySelector("#lat").value =  feedback.selection.value.lat ;
    document.querySelector("#lng").value =  feedback.selection.value.lng ;
    document.querySelector("#city").value =  feedback.selection.value.city ;
    feedback = null;
}
})