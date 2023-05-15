class tituloHTML extends HTMLElement{
    constructor(){
        super();
    }
    connectedCallback(){
        this.style.color = "red";
        this.style.fontSize = "30px";
        this.style.fontWeight = "bold"; 
    }
}
customElements.define("titulo-html", tituloHTML);

class subtituloHTML extends HTMLElement{
    constructor(){
        super();
    }
    connectedCallback(){
        this.style.color = "blue";
        this.style.fontSize = "25px";
        this.style.fontStyle = "italic";
    }
}
customElements.define("subtitulo-html", subtituloHTML);

class textoHTML extends HTMLElement{
    constructor(){
        super();
    }
    connectedCallback(){
        this.innerHTML = `<br><br>`;
        this.style.color = "black";
        this.style.fontSize = "14px";
    }
}

customElements.define("texto-html", textoHTML);

class notaHTML extends HTMLElement{
    constructor(){
        super();
    }
    connectedCallback(){
        this.style.color = "white";
        this.style.fontSize = "14px";
        this.style.backgroundColor = "green";
        this.style.padding = "5px";
    }
}

customElements.define("nota-html", notaHTML);