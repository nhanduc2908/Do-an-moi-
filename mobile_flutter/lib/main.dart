import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:hive_flutter/hive_flutter.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';

import 'core/constants/app_constants.dart';
import 'core/constants/storage_keys.dart';
import 'core/theme/app_theme.dart';
import 'core/routes/app_routes.dart';
import 'core/routes/route_names.dart';
import 'core/utils/secure_storage.dart';
import 'core/utils/logger.dart';
import 'firebase/firebase_options.dart';
import 'localization/app_localizations.dart';
import 'presentation/providers/theme_provider.dart';
import 'presentation/providers/language_provider.dart';
import 'presentation/providers/auth_provider.dart';
import 'presentation/providers/network_provider.dart';
import 'presentation/providers/sync_provider.dart';
import 'presentation/providers/notification_provider.dart';
import 'presentation/providers/role_provider.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Load environment variables
  await dotenv.load(fileName: ".env");

  // Initialize logger
  Logger.init();

  // Initialize Firebase
  await Firebase.initializeApp(
    options: DefaultFirebaseOptions.currentPlatform,
  );

  // Initialize Hive for local storage
  await Hive.initFlutter();
  await Hive.openBox(StorageKeys.userBox);
  await Hive.openBox(StorageKeys.settingsBox);
  await Hive.openBox(StorageKeys.cacheBox);
  await Hive.openBox(StorageKeys.syncQueueBox);
  await Hive.openBox(StorageKeys.offlineBox);

  // Initialize secure storage
  await SecureStorage.init();

  // Run app
  runApp(const ProviderScope(child: MyApp()));
}

class MyApp extends ConsumerStatefulWidget {
  const MyApp({super.key});

  @override
  ConsumerState<MyApp> createState() => _MyAppState();
}

class _MyAppState extends ConsumerState<MyApp> {
  @override
  void initState() {
    super.initState();
    _initializeApp();
  }

  Future<void> _initializeApp() async {
    // Check authentication status
    await ref.read(authProvider.notifier).checkAuthStatus();

    // Initialize network monitoring
    ref.read(networkProvider.notifier).initialize();

    // Initialize sync service
    ref.read(syncProvider.notifier).initialize();

    // Initialize notification service
    ref.read(notificationProvider.notifier).initialize();

    Logger.info('App initialized successfully');
  }

  @override
  Widget build(BuildContext context) {
    final themeMode = ref.watch(themeProvider);
    final locale = ref.watch(languageProvider);
    final authState = ref.watch(authProvider);
    final router = AppRoutes.router;

    return MaterialApp.router(
      title: AppConstants.appName,
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      darkTheme: AppTheme.darkTheme,
      themeMode: themeMode,
      locale: locale,
      localizationsDelegates: const [
        AppLocalizations.delegate,
        GlobalMaterialLocalizations.delegate,
        GlobalWidgetsLocalizations.delegate,
        GlobalCupertinoLocalizations.delegate,
      ],
      supportedLocales: AppConstants.supportedLocales,
      routerConfig: router,
    );
  }
}
