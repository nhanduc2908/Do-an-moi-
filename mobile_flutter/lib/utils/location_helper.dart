// Đường dẫn: mobile_flutter/lib/utils/location_helper.dart

import 'package:geolocator/geolocator.dart';

class LocationHelper {
  static Future<bool> hasPermission() async {
    final permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      final result = await Geolocator.requestPermission();
      return result == LocationPermission.always || result == LocationPermission.whileInUse;
    }
    return true;
  }

  static Future<Position?> getCurrentLocation() async {
    final hasPerm = await hasPermission();
    if (!hasPerm) return null;
    
    try {
      return await Geolocator.getCurrentPosition();
    } catch (e) {
      return null;
    }
  }

  static Future<double> getDistanceBetween(double lat1, double lon1, double lat2, double lon2) async {
    return Geolocator.distanceBetween(lat1, lon1, lat2, lon2);
  }
}