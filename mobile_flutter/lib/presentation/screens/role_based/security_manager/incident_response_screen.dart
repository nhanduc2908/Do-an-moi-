import 'package:flutter/material.dart';

class IncidentResponseScreen extends StatefulWidget {
  const IncidentResponseScreen({super.key});

  @override
  State<IncidentResponseScreen> createState() => _IncidentResponseScreenState();
}

class _IncidentResponseScreenState extends State<IncidentResponseScreen> {
  final List<Map<String, dynamic>> _incidents = [
    {'code': 'INC-001', 'title': 'Unauthorized Access Attempt', 'severity': 'Critical', 'status': 'Active', 'assigned': 'John Doe'},
    {'code': 'INC-002', 'title': 'Malware Detection', 'severity': 'High', 'status': 'Investigating', 'assigned': 'Jane Smith'},
    {'code': 'INC-003', 'title': 'Data Breach Suspected', 'severity': 'Critical', 'status': 'Containment', 'assigned': 'Mike Johnson'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Incident Response'),
        backgroundColor: Colors.red.shade700,
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _incidents.length,
        itemBuilder: (context, index) {
          final incident = _incidents[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: _getSeverityColor(incident['severity']),
                child: Text(incident['severity'].substring(0, 1), style: const TextStyle(color: Colors.white)),
              ),
              title: Text(incident['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Code: ${incident['code']} • Status: ${incident['status']}'),
                  Text('Assigned to: ${incident['assigned']}', style: const TextStyle(fontSize: 12)),
                ],
              ),
              trailing: ElevatedButton(
                onPressed: () {},
                style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
                child: const Text('Respond', style: TextStyle(color: Colors.white)),
              ),
              onTap: () {},
            ),
          );
        },
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () {},
        icon: const Icon(Icons.play_arrow),
        label: const Text('Run Playbook'),
        backgroundColor: Colors.red,
      ),
    );
  }

  Color _getSeverityColor(String severity) {
    switch (severity) {
      case 'Critical': return Colors.red;
      case 'High': return Colors.orange;
      default: return Colors.yellow.shade700;
    }
  }
}