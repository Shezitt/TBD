
const express = require('express');
const path = require('path');
const app = express();
const port = 3000;

// Middleware para leer datos de formularios
app.use(express.urlencoded({ extended: true }));

// Servir archivos estáticos (HTML, CSS, etc.)
app.use(express.static(path.join(__dirname, 'public')));

// Ruta principal
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.listen(port, () => {
  console.log(`Servidor Express corriendo en http://localhost:${port}`);
});
