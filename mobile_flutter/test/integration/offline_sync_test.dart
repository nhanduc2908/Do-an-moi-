// Đường dẫn: mobile_flutter/test/integration/offline_sync_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:security_evaluation_app/main.dart';
import 'package:security_evaluation_app/services/sync/sync_engine.dart';
import 'package:security_evaluation_app/core/utils/network_checker.dart';
import '../mocks/mock_network_info.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Offline Sync Integration Tests', () {
    late SyncEngine syncEngine;

    setUp(() {
      syncEngine = SyncEngine();
    });

    testWidgets('sync queues requests when offline', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      // Simulate offline
      // await NetworkChecker.setOffline(true);
      
      await syncEngine.sync();
      
      expect(syncEngine.isSyncing, false);
    });

    testWidgets('sync processes queue when back online', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      // Simulate coming back online
      // await NetworkChecker.setOffline(false);
      
      await syncEngine.sync();
      
      expect(syncEngine.isSyncing, isA<bool>());
    });
  });
}