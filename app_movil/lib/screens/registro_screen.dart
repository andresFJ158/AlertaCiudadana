import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:geolocator/geolocator.dart';
import 'package:image_picker/image_picker.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../services/incident_service.dart';

class RegistroScreen extends StatefulWidget {
  @override
  _RegistroScreenState createState() => _RegistroScreenState();
}

class _RegistroScreenState extends State<RegistroScreen> {
  final _formKey = GlobalKey<FormState>();
  String _categoria = 'Baches';
  String _descripcion = '';
  Position? _posicionActual;
  List<XFile> _evidencias = [];
  final ImagePicker _picker = ImagePicker();

  @override
  void initState() {
    super.initState();
    _cargarBorrador(); // Intenta recuperar datos no enviados al iniciar la pantalla
  }

  Future<void> _cargarBorrador() async {
    final prefs = await SharedPreferences.getInstance();
    final borradorString = prefs.getString('borrador_incidente');
    
    if (borradorString != null) {
      final borrador = jsonDecode(borradorString);
      setState(() {
        _categoria = borrador['categoria'] ?? 'Baches';
        _descripcion = borrador['descripcion'] ?? '';
        // Las rutas de imágenes se recuperan como XFile
        if (borrador['evidencias'] != null) {
          List<dynamic> rutas = borrador['evidencias'];
          _evidencias = rutas.map((ruta) => XFile(ruta.toString())).toList();
        }
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Borrador local cargado.')),
      );
    }
  }

  Future<void> _guardarBorrador() async {
    final prefs = await SharedPreferences.getInstance();
    final borrador = {
      'categoria': _categoria,
      'descripcion': _descripcion,
      'evidencias': _evidencias.map((e) => e.path).toList(),
    };
    await prefs.setString('borrador_incidente', jsonEncode(borrador));
  }

  Future<void> _limpiarBorrador() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('borrador_incidente');
  }

  Future<void> _obtenerUbicacion() async {
    bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) return;
    LocationPermission permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
    }
    if (permission == LocationPermission.whileInUse || permission == LocationPermission.always) {
      _posicionActual = await Geolocator.getCurrentPosition();
      setState(() {});
    }
  }

  Future<void> _seleccionarImagenes() async {
    if (_evidencias.length >= 5) return; // Límite de 5 archivos[cite: 4]
    final List<XFile>? imagenes = await _picker.pickMultiImage();
    if (imagenes != null) {
      setState(() {
        _evidencias.addAll(imagenes.take(5 - _evidencias.length));
      });
    }
  }

  void _enviarReporte() async {
    if (_formKey.currentState!.validate() && _posicionActual != null) {
      _formKey.currentState!.save();

      Map<String, dynamic> datosIncidente = {
        'categoria': _categoria,
        'descripcion': _descripcion,
        'latitud': _posicionActual!.latitude,
        'longitud': _posicionActual!.longitude,
      };

      List<String> rutasImagenes = _evidencias.map((img) => img.path).toList();

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Enviando reporte...')),
      );

      final servicio = IncidentService();
      String? uuidGenerado = await servicio.registrarIncidente(datosIncidente, rutasImagenes);

      if (uuidGenerado != null) {
        await _limpiarBorrador(); // Borra el respaldo local tras un envío exitoso
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Incidente registrado. UUID: $uuidGenerado')),
        );
        Navigator.pop(context); 
      } else {
        await _guardarBorrador(); // Guarda los datos si falla la red[cite: 8]
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error de red. Borrador guardado localmente para reintento.')),
        );
      }
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Por favor, completa la descripción y captura la ubicación.')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Registrar Incidencia')),
      body: Form(
        key: _formKey,
        child: ListView(
          padding: EdgeInsets.all(16),
          children: [
            DropdownButtonFormField<String>(
              value: _categoria,
              items: ['Baches', 'Alumbrado', 'Residuos', 'Tránsito', 'Seguridad']
                  .map((c) => DropdownMenuItem(value: c, child: Text(c))).toList(),
              onChanged: (val) => setState(() => _categoria = val!),
            ),
            TextFormField(
              initialValue: _descripcion, // Se enlaza al estado para cargar borradores
              decoration: InputDecoration(labelText: 'Descripción'),
              onSaved: (val) => _descripcion = val!,
            ),
            ElevatedButton.icon(
              icon: Icon(Icons.location_on),
              label: Text(_posicionActual == null ? 'Obtener Ubicación' : 'Ubicación Capturada'),
              onPressed: _obtenerUbicacion,
            ),
            ElevatedButton.icon(
              icon: Icon(Icons.camera_alt),
              label: Text('Adjuntar Evidencias (${_evidencias.length}/5)'),
              onPressed: _seleccionarImagenes,
            ),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: _enviarReporte,
              child: Text('Enviar Reporte'),
            )
          ],
        ),
      ),
    );
  }
}