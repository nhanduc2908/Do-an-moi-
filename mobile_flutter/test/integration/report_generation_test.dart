// Đường dẫn: mobile_flutter/test/integration/report_generation_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:security_evaluation_app/main.dart';
import 'package:security_evaluation_app/presentation/screens/report/report_list_screen.dart';
import 'package:security_evaluation_app/presentation/screens/report/generate_report_screen.dart';
import 'package:security_evaluation_app/presentation/screens/report/export_report_screen.dart';
import '../helpers/test_helpers.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Report Generation Flow Integration Tests', () {
    testWidgets('can navigate to report list screen', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      await tester.tap(find.text('Reports'));
      await tester.pumpAndSettle();

      expect(find.byType(ReportListScreen), findsOneWidget);
    });

    testWidgets('can navigate to generate report screen', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: ReportListScreen(),
        ),
      );
      await tester.pumpAndSettle();

      await tester.tap(find.byIcon(Icons.add));
      await tester.pumpAndSettle();

      expect(find.byType(GenerateReportScreen), findsOneWidget);
    });

    testWidgets('generates report with valid inputs', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: GenerateReportScreen(),
        ),
      );
      await tester.pumpAndSettle();

      // Select report type
      await tester.tap(find.text('Security Summary'));
      await tester.pumpAndSettle();
      await tester.tap(find.text('Security Summary').last);
      await tester.pump();

      // Select format
      await tester.tap(find.text('PDF'));
      await tester.pumpAndSettle();
      await tester.tap(find.text('PDF').last);
      await tester.pump();

      // Generate report
      await tester.tap(find.text('Generate Report'));
      await tester.pumpAndSettle();

      expect(find.text('Report generated successfully'), findsOneWidget);
    });

    testWidgets('shows error for invalid report parameters', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: GenerateReportScreen(),
        ),
      );
      await tester.pumpAndSettle();

      // Try to generate without selecting type
      await tester.tap(find.text('Generate Report'));
      await tester.pump();

      expect(find.text('Please select report type'), findsOneWidget);
    });

    testWidgets('can export generated report', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: ExportReportScreen(reportId: '1'),
        ),
      );
      await tester.pumpAndSettle();

      // Select format
      await tester.tap(find.text('PDF'));
      await tester.pumpAndSettle();
      await tester.tap(find.text('PDF').last);
      await tester.pump();

      // Export
      await tester.tap(find.text('Export Report'));
      await tester.pumpAndSettle();

      expect(find.text('Report exported as PDF'), findsOneWidget);
    });

    testWidgets('can schedule recurring reports', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: ScheduleReportScreen(),
        ),
      );
      await tester.pumpAndSettle();

      // Select frequency
      await tester.tap(find.text('Weekly'));
      await tester.pumpAndSettle();
      await tester.tap(find.text('Weekly').last);
      await tester.pump();

      // Select time
      await tester.tap(find.text('Time: 8:00 AM'));
      await tester.pumpAndSettle();
      await tester.tap(find.text('OK'));
      await tester.pump();

      // Schedule
      await tester.tap(find.text('Schedule Report'));
      await tester.pumpAndSettle();

      expect(find.text('Report scheduled successfully'), findsOneWidget);
    });

    testWidgets('can share report', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: ShareReportScreen(reportId: '1'),
        ),
      );
      await tester.pumpAndSettle();

      // Add recipient
      await tester.enterText(find.byType(TextField).first, 'test@example.com');
      await tester.tap(find.byIcon(Icons.add_circle));
      await tester.pump();

      // Share
      await tester.tap(find.text('Share Report'));
      await tester.pumpAndSettle();

      expect(find.text('Report shared successfully'), findsOneWidget);
    });

    testWidgets('shows error when sharing without recipients', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: ShareReportScreen(reportId: '1'),
        ),
      );
      await tester.pumpAndSettle();

      await tester.tap(find.text('Share Report'));
      await tester.pump();

      expect(find.text('Please add at least one recipient'), findsOneWidget);
    });

    testWidgets('can preview report before export', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: ReportPreviewScreen(reportId: '1'),
        ),
      );
      await tester.pumpAndSettle();

      expect(find.byType(ReportPreview), findsOneWidget);
      expect(find.text('Preview'), findsOneWidget);
    });
  });
}