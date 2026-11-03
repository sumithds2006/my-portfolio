// Navigation click message

document.querySelectorAll("nav a").forEach(function(link) {

```
link.addEventListener("click", function() {

    console.log("You selected: " + this.textContent);

});
```

});

// Welcome message

window.addEventListener("load", function() {

```
console.log("Welcome to Sumith's Portfolio!");
```

});
