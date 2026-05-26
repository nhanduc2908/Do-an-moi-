// Đường dẫn: mobile_flutter/test/integration/incident_reporting_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:security_evaluation_app/main.dart';
import 'package:security_evaluation_app/presentation/screens/incident/incident_list_screen.dart';
import 'package:security_evaluation_app/presentation/screens/incident/report_incident_screen.dart';
import 'package:security_evaluation_app/presentation/screens/incident/incident_detail_screen.dart';
import '../helpers/test_helpers.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Incident Reporting Flow Integration Tests', () {
    testWidgets('can navigate to incident list screen', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      await tester.tap(find.text('Incidents'));
      await tester.pumpAndSettle();

      expect(find.byType(IncidentListScreen), findsOneWidget);
    });

    testWidgets('can navigate to report incident screen', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: IncidentListScreen(),
        ),
      );
      await tester.pumpAndSettle();

      await tester.tap(find.byIcon(Icons.add));
      await tester.pumpAndSettle();

      expect(find.byType(ReportIncidentScreen), findsOneWidget);
    });

    testWidgets('reports incident with valid data', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: ReportIncidentScreen(),
        ),
      );
      await tester.pumpAndSettle();

      // Enter title
      await tester.enterText(find.byType(TextField).first, 'Security Breach Detected');
      
      // Enter description
      await tester.enterText(find.byType(TextField).last, 'Unauthorized access detected from IP 192.168.1.100');
      
      // Select severity
      await tester.tap(find.text('Medium'));
      await tester.pumpAndSettle();
      await tester.tap(find.text('High').last);
      await tester.pump();

      // Select category
      await tester.tap(find.text('Unauthorized Access'));
      await tester.pumpAndSettle();
      await tester.tap(find.text('Unauthorized Access').last);
      await tester.pump();

      // Submit
      await tester.tap(find.text('Report Incident'));
      await tester.pumpAndSettle();

      expect(find.text('Incident reported successfully'), findsOneWidget);
    });

    testWidgets('shows error when title is empty', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: ReportIncidentScreen(),
        ),
      );
      await tester.pumpAndSettle();

      await tester.tap(find.text('Report Incident'));
      await tester.pump();

      expect(find.text('Please enter incident title'), findsOneWidget);
    });

    testWidgets('can view incident details', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: IncidentDetailScreen(incidentId: '1'),
        ),
      );
      await tester.pumpAndSettle();

      expect(find.byType(IncidentDetailScreen), findsOneWidget);
      expect(find.text('Incident Details'), findsOneWidget);
    });

    testWidgets('can add comment to incident', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: IncidentDetailScreen(incidentId: '1'),
        ),
      );
      await tester.pumpAndSettle();

      await tester.enterText(find.byType(TextField).last, 'Investigating this issue');
      await tester.tap(find.text('Add Comment'));
      await tester.pumpAndSettle();

      expect(find.text('Comment added successfully'), findsOneWidget);
    });

    testWidgets('can update incident status', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: IncidentDetailScreen(incidentId: '1'),
        ),
      );
      await tester.pumpAndSettle();

      await tester.tap(find.text('Update Status'));
      await tester.pumpAndSettle();

      await tester.tap(find.text('In Progress'));
      await tester.pumpAndSettle();
      await tester.tap(find.text('In Progress').last);
      await tester.pump();

      await tester.tap(find.text('Save'));
      await tester.pumpAndSettle();

      expect(find.text('Status updated successfully'), findsOneWidget);
    });

    testWidgets('can filter incidents by severity', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: IncidentListScreen(),
        ),
      );
      await tester.pumpAndSettle();

      await tester.tap(find.byIcon(Icons.filter_list));
      await tester.pumpAndSettle();

      await tester.tap(find.text('Critical'));
      await tester.pumpAndSettle();
      await tester.tap(find.text('Critical').last);
      await tester.pump();

      await tester.tap(find.text('Apply'));
      await tester.pumpAndSettle();

      expect(find.byType(IncidentListScreen), findsOneWidget);
    });

    testWidgets('can filter incidents by status', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: IncidentListScreen(),
        ),
      );
      await tester.pumpAndSettle();

      await tester.tap(find.byIcon(Icons.filter_list));
      await tester.pumpAndSettle();

      await tester.tap(find.text('Open'));
      await tester.pumpAndSettle();
      await tester.tap(find.text('Open').last);
      await tester.pump();

      await tester.tap(find.text('Apply'));
      await tester.pumpAndSettle();

      expect(find.byType(IncidentListScreen), findsOneWidget);
    });

    testWidgets('can search incidents', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: IncidentListScreen(),
        ),
      );
      await tester.pumpAndSettle();

      await tester.enterText(find.byType(TextField), 'Security');
      await tester.pumpAndSettle();

      expect(find.text('Security Breach Detected'), findsOneWidget);
    });

    testWidgets('shows empty state when no incidents', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: IncidentListScreen(),
        ),
      );
      await tester.pumpAndSettle();

      // Search for non-existent incident
      await tester.enterText(find.byType(TextField), 'NonExistentIncident');
      await tester.pumpAndSettle();

      expect(find.text('No incidents found'), findsOneWidget);
    });
  });
}