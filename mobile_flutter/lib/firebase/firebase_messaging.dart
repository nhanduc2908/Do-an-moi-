// Đường dẫn: mobile_flutter/lib/firebase/firebase_messaging.dart

import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';

class FirebaseMessagingService {
  final FirebaseMessaging _firebaseMessaging = FirebaseMessaging.instance;

  Future<void> initialize() async {
    await _firebaseMessaging.requestPermission();
    final token = await _firebaseMessaging.getToken();
    debugPrint('FCM Token: $token');

    FirebaseMessaging.onMessage.listen(_handleMessage);
    FirebaseMessaging.onMessageOpenedApp.listen(_handleMessageOpened);
  }

  void _handleMessage(RemoteMessage message) {
    debugPrint('Message received: ${message.notification?.title}');
  }

  void _handleMessageOpened(RemoteMessage message) {
    debugPrint('Message opened: ${message.notification?.title}');
  }

  Future<String?> getToken() async {
    return await _firebaseMessaging.getToken();
  }
}