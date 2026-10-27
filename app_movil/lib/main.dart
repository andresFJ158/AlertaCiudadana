import 'package:flutter/material.dart';
import 'screens/registro_screen.dart';
import 'screens/consulta_screen.dart';

void main() {
  runApp(AlertaCiudadanaApp());
}

class AlertaCiudadanaApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Alerta Ciudadana',
      theme: ThemeData(primarySwatch: Colors.blue),
      home: InicioScreen(),
    );
  }
}

class InicioScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Gestión de Incidencias')),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            ElevatedButton(
              onPressed: () => Navigator.push(
                context, 
                MaterialPageRoute(builder: (context) => RegistroScreen())
              ),
              child: Text('Registrar Incidente'),
            ),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: () => Navigator.push(
                context, 
                MaterialPageRoute(builder: (context) => ConsultaScreen())
              ),
              child: Text('Consultar Incidente'),
            ),
          ],
        ),
      ),
    );
  }
}