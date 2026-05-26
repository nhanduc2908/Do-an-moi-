import 'package:permission_handler/permission_handler.dart';

class PermissionHelper {
  static Future<bool> requestCameraPermission() async {
    final status = await Permission.camera.request();
    return status.isGranted;
  }
  
  static Future<bool> requestStoragePermission() async {
    final status = await Permission.storage.request();
    return status.isGranted;
  }
  
  static Future<bool> requestNotificationPermission() async {
    final status = await Permission.notification.request();
    return status.isGranted;
  }
  
  static Future<bool> requestLocationPermission() async {
    final status = await Permission.location.request();
    return status.isGranted;
  }
  
  static Future<bool> requestMicrophonePermission() async {
    final status = await Permission.microphone.request();
    return status.isGranted;
  }
  
  static Future<bool> requestContactsPermission() async {
    final status = await Permission.contacts.request();
    return status.isGranted;
  }
  
  static Future<bool> requestCalendarPermission() async {
    final status = await Permission.calendar.request();
    return status.isGranted;
  }
  
  static Future<bool> requestSmsPermission() async {
    final status = await Permission.sms.request();
    return status.isGranted;
  }
  
  static Future<bool> requestPhonePermission() async {
    final status = await Permission.phone.request();
    return status.isGranted;
  }
  
  static Future<bool> requestPermissions(List<Permission> permissions) async {
    final statuses = await permissions.request();
    return statuses.values.every((status) => status.isGranted);
  }
  
  static Future<PermissionStatus> checkCameraPermission() async {
    return await Permission.camera.status;
  }
  
  static Future<PermissionStatus> checkStoragePermission() async {
    return await Permission.storage.status;
  }
  
  static Future<bool> isCameraGranted() async {
    return (await Permission.camera.status).isGranted;
  }
  
  static Future<bool> isStorageGranted() async {
    return (await Permission.storage.status).isGranted;
  }
  
  static Future<void> openAppSettings() async {
    await openAppSettings();
  }
  
  static Future<bool> shouldShowRequestRationale(Permission permission) async {
    return await permission.shouldShowRequestRationale;
  }
}