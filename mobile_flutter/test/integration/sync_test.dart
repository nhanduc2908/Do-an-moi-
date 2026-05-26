// Đường dẫn: mobile_flutter/test/integration/sync_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:security_evaluation_app/main.dart';
import 'package:security_evaluation_app/services/sync/sync_engine.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Sync Integration Tests', () {
    late SyncEngine syncEngine;

    setUp(() {
      syncEngine = SyncEngine();
    });

    testWidgets('sync engine initializes correctly', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      expect(syncEngine.isSyncing, false);
    });

    testWidgets('manual sync can be triggered', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      await syncEngine.forceSync();
      
      expect(syncEngine.isSyncing, true);
    });
  });
}