import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import '../services/incident_service.dart';

class ConsultaScreen extends StatefulWidget {
  @override
  _ConsultaScreenState createState() => _ConsultaScreenState();
}

class _ConsultaScreenState extends State<ConsultaScreen> {
  final TextEditingController _uuidController = TextEditingController();
  Map<String, dynamic>? _incidenteConsultado;
  bool _cargando = false;
  String? _mensajeError;
  GoogleMapController? _mapController;

  void _buscarIncidente() async {
    if (_uuidController.text.trim().isEmpty) return;

    setState(() {
      _cargando = true;
      _mensajeError = null;
      _incidenteConsultado = null;
    });

    final servicio = IncidentService();
    final resultado = await servicio.consultarIncidente(_uuidController.text.trim());

    setState(() {
      _cargando = false;
      if (resultado != null) {
        _incidenteConsultado = resultado;
        
        // Aquí utilizamos el _mapController para mover la cámara si el mapa ya está cargado
        if (_mapController != null) {
          double lat = double.parse(resultado['latitud'].toString());
          double lng = double.parse(resultado['longitud'].toString());
          
          _mapController!.animateCamera(
            CameraUpdate.newLatLng(LatLng(lat, lng)),
          );
        }
      } else {
        _mensajeError = 'Incidente no encontrado. Verifica el UUID ingresado.';
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 2,
      child: Scaffold(
        appBar: AppBar(
          title: Text('Consultar Reportes'),
          bottom: TabBar(tabs: [
            Tab(icon: Icon(Icons.list), text: 'Detalle Accesible'),
            Tab(icon: Icon(Icons.map), text: 'Ubicación'),
          ]),
        ),
        body: TabBarView(
          children: [
            // Vista de Lista con Filtros (RF-07)[cite: 4]
            Padding(
              padding: EdgeInsets.all(16.0),
              child: Column(
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: TextField(
                          controller: _uuidController,
                          decoration: InputDecoration(
                            labelText: 'Ingrese UUID del incidente',
                            border: OutlineInputBorder(),
                          ),
                        ),
                      ),
                      SizedBox(width: 10),
                      ElevatedButton(
                        onPressed: _cargando ? null : _buscarIncidente,
                        child: _cargando 
                            ? SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2)) 
                            : Icon(Icons.search),
                      )
                    ],
                  ),
                  SizedBox(height: 20),
                  if (_mensajeError != null)
                    Text(_mensajeError!, style: TextStyle(color: Colors.red)),
                  if (_incidenteConsultado != null)
                    Expanded(
                      child: ListView(
                        children: [
                          ListTile(
                            title: Text('Categoría', style: TextStyle(fontWeight: FontWeight.bold)),
                            subtitle: Text('${_incidenteConsultado!['categoria']}'),
                          ),
                          ListTile(
                            title: Text('Estado', style: TextStyle(fontWeight: FontWeight.bold)),
                            subtitle: Text('${_incidenteConsultado!['estado']}'),
                          ),
                          ListTile(
                            title: Text('Descripción', style: TextStyle(fontWeight: FontWeight.bold)),
                            subtitle: Text('${_incidenteConsultado!['descripcion'] ?? 'Sin descripción proporcionada'}'),
                          ),
                        ],
                      ),
                    ),
                ],
              ),
            ),
            // Vista de Mapa (RF-07)[cite: 4]
            _incidenteConsultado == null
                ? Center(child: Text('Busque un incidente para ver su ubicación.'))
                : GoogleMap(
                    onMapCreated: (controller) => _mapController = controller,
                    initialCameraPosition: CameraPosition(
                      target: LatLng(
                        double.parse(_incidenteConsultado!['latitud'].toString()),
                        double.parse(_incidenteConsultado!['longitud'].toString()),
                      ),
                      zoom: 15.0,
                    ),
                    markers: {
                      Marker(
                        markerId: MarkerId(_incidenteConsultado!['uuid'] ?? 'incidente_actual'),
                        position: LatLng(
                          double.parse(_incidenteConsultado!['latitud'].toString()),
                          double.parse(_incidenteConsultado!['longitud'].toString()),
                        ),
                        infoWindow: InfoWindow(
                          title: _incidenteConsultado!['categoria'],
                          snippet: _incidenteConsultado!['estado'],
                        ),
                      ),
                    },
                  ),
          ],
        ),
      ),
    );
  }
}