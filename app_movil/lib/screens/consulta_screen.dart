import 'package:flutter/material.dart';

class ConsultaScreen extends StatefulWidget {
  @override
  _ConsultaScreenState createState() => _ConsultaScreenState();
}

class _ConsultaScreenState extends State<ConsultaScreen> {
  final TextEditingController _uuidController = TextEditingController();
  Map<String, dynamic>? _incidenteConsultado;

  void _buscarIncidente() {
    // Aquí se invoca IncidentService().consultarIncidente(_uuidController.text)
    // Se actualiza el estado con los resultados
  }

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 2,
      child: Scaffold(
        appBar: AppBar(
          title: Text('Consultar Reportes'),
          bottom: TabBar(tabs: [
            Tab(icon: Icon(Icons.list), text: 'Lista Accesible'),
            Tab(icon: Icon(Icons.map), text: 'Mapa'),
          ]),
        ),
        body: TabBarView(
          children: [
            // Vista de Lista con Filtros (RF-07)[cite: 4]
            Column(
              children: [
                Padding(
                  padding: EdgeInsets.all(16.0),
                  child: Row(
                    children: [
                      Expanded(
                        child: TextField(
                          controller: _uuidController,
                          decoration: InputDecoration(labelText: 'Ingrese UUID del incidente'),
                        ),
                      ),
                      IconButton(icon: Icon(Icons.search), onPressed: _buscarIncidente)
                    ],
                  ),
                ),
                _incidenteConsultado != null
                    ? ListTile(
                        title: Text('Categoría: ${_incidenteConsultado!['categoria']}'),
                        subtitle: Text('Estado: ${_incidenteConsultado!['estado']}'),
                      )
                    : Center(child: Text('Ingrese un UUID válido para consultar')),
              ],
            ),
            // Vista de Mapa (RF-07)[cite: 4]
            Center(
              child: Text('Integración de Mapa interactivo (ej. google_maps_flutter)'),
            )
          ],
        ),
      ),
    );
  }
}