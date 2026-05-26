import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/incident_provider.dart';
import '../../widgets/common/custom_button.dart';

class IncidentClosedScreen extends ConsumerStatefulWidget {
  const IncidentClosedScreen({super.key});

  @override
  ConsumerState<IncidentClosedScreen> createState() => _IncidentClosedScreenState();
}

class _IncidentClosedScreenState extends ConsumerState<IncidentClosedScreen> {
  final List<Map<String, dynamic>> _closedIncidents = [
    {'code': 'INC-001', 'title': 'Suspicious Login', 'resolved': '2024-01-10', 'resolution': 'Blocked IP'},
    {'code': 'INC-002', 'title': 'Malware Detection', 'resolved': '2024-01-08', 'resolution': 'Removed malware'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Closed Incidents'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _closedIncidents.length,
        itemBuilder: (context, index) {
          final incident = _closedIncidents[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              leading: const CircleAvatar(
                backgroundColor: Colors.green,
                child: Icon(Icons.check, color: Colors.white),
              ),
              title: Text(incident['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${incident['code']} • Resolved: ${incident['resolved']}'),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Resolution:', style: TextStyle(fontWeight: FontWeight.bold)),
                      Text(incident['resolution']),
                      const SizedBox(height: 12),
                      CustomButton(
                        text: 'View Report',
                        onPressed: () {},
                        isOutlined: true,
                      ),
                    ],
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}