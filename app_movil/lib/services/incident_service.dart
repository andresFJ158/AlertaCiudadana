import 'dart:convert';
import 'package:http/http.dart' as http;

class IncidentService {
  static const String baseUrl = 'https://tu-api-laravel.com/api/v1/incidents'; // Ajustar según servidor de Jorge

  // Registro: Envía datos y retorna el UUID
  Future<String?> registrarIncidente(Map<String, dynamic> data, List<String> imagePaths) async {
    var request = http.MultipartRequest('POST', Uri.parse(baseUrl));
    
    request.fields['categoria'] = data['categoria'];
    request.fields['descripcion'] = data['descripcion'];
    request.fields['latitud'] = data['latitud'].toString();
    request.fields['longitud'] = data['longitud'].toString();

    for (var path in imagePaths) {
      request.files.add(await http.MultipartFile.fromPath('evidencias[]', path));
    }

    var response = await request.send();
    if (response.statusCode == 201) {
      var responseData = await response.stream.bytesToString();
      return jsonDecode(responseData)['uuid'];
    }
    return null;
  }

  // Consulta: Retorna datos del incidente sin exponer contacto[cite: 4, 7]
  Future<Map<String, dynamic>?> consultarIncidente(String uuid) async {
    final response = await http.get(Uri.parse('$baseUrl/$uuid'));
    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      // RNF-04: Eliminación explícita de datos sensibles antes de renderizar[cite: 7]
      data.remove('contacto'); 
      data.remove('telefono');
      return data;
    }
    return null;
  }
}