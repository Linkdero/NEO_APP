function imprimir() {
    window.print();
    window.onafterprint=function(){ window.close();};
}