// Đường dẫn: mobile_flutter/lib/firebase/firebase_options.dart

import 'package:firebase_core/firebase_core.dart' show FirebaseOptions;
import 'package:flutter/foundation.dart' show kIsWeb, TargetPlatform, defaultTargetPlatform;

class DefaultFirebaseOptions {
  static FirebaseOptions get currentPlatform {
    if (kIsWeb) {
      return web;
    }
    switch (defaultTargetPlatform) {
      case TargetPlatform.android:
        return android;
      case TargetPlatform.iOS:
        return ios;
      case TargetPlatform.macOS:
        return macos;
      default:
        throw UnsupportedError(
          'DefaultFirebaseOptions are not supported for this platform.',
        );
    }
  }

  static const FirebaseOptions web = FirebaseOptions(
    apiKey: 'AIzaSyXXXXXXXXXXXXXX',
    appId: '1:123456789012:web:xxxxxx',
    messagingSenderId: '123456789012',
    projectId: 'security-platform-xxx',
    authDomain: 'security-platform-xxx.firebaseapp.com',
    storageBucket: 'security-platform-xxx.appspot.com',
  );

  static const FirebaseOptions android = FirebaseOptions(
    apiKey: 'AIzaSyXXXXXXXXXXXXXX',
    appId: '1:123456789012:android:xxxxxx',
    messagingSenderId: '123456789012',
    projectId: 'security-platform-xxx',
    storageBucket: 'security-platform-xxx.appspot.com',
  );

  static const FirebaseOptions ios = FirebaseOptions(
    apiKey: 'AIzaSyXXXXXXXXXXXXXX',
    appId: '1:123456789012:ios:xxxxxx',
    messagingSenderId: '123456789012',
    projectId: 'security-platform-xxx',
    storageBucket: 'security-platform-xxx.appspot.com',
    androidClientId: 'xxxxxxxxxx.apps.googleusercontent.com',
    iosClientId: 'xxxxxxxxxx.apps.googleusercontent.com',
    iosBundleId: 'com.security.evaluation.app',
  );

  static const FirebaseOptions macos = FirebaseOptions(
    apiKey: 'AIzaSyXXXXXXXXXXXXXX',
    appId: '1:123456789012:ios:xxxxxx',
    messagingSenderId: '123456789012',
    projectId: 'security-platform-xxx',
    storageBucket: 'security-platform-xxx.appspot.com',
    androidClientId: 'xxxxxxxxxx.apps.googleusercontent.com',
    iosClientId: 'xxxxxxxxxx.apps.googleusercontent.com',
    iosBundleId: 'com.security.evaluation.app',
  );
}