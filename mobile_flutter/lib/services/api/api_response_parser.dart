import 'dart:convert';

class ApiResponseParser {
  static T parse<T>(dynamic response, T Function(dynamic) fromJson) {
    if (response is String) {
      return fromJson(jsonDecode(response));
    }
    return fromJson(response);
  }

  static List<T> parseList<T>(dynamic response, T Function(dynamic) fromJson) {
    final List<dynamic> list;
    if (response is String) {
      list = jsonDecode(response);
    } else {
      list = response as List<dynamic>;
    }
    return list.map((item) => fromJson(item)).toList();
  }

  static Map<String, dynamic> toMap(dynamic data) {
    if (data is Map) {
      return Map<String, dynamic>.from(data);
    }
    return jsonDecode(jsonEncode(data));
  }
}