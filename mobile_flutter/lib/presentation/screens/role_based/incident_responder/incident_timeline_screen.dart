import 'package:flutter/material.dart';

class IncidentTimelineScreen extends StatefulWidget {
  const IncidentTimelineScreen({super.key, this.incidentId});
  final String? incidentId;

  @override
  State<IncidentTimelineScreen> createState() => _IncidentTimelineScreenState();
}

class _IncidentTimelineScreenState extends State<IncidentTimelineScreen> {
  String _selectedIncident = 'INC-001';
  
  final Map<String, List<Map<String, dynamic>>> _timelines = {
    'INC-001': [
      {'time': '10:30:00', 'action': 'Incident detected by IDS', 'actor': 'System', 'type': 'detection', 'details': 'Suspicious traffic pattern detected from IP 185.130.5.253'},
      {'time': '10:32:15', 'action': 'Alert triggered', 'actor': 'SIEM', 'type': 'alert', 'details': 'Critical severity alert generated'},
      {'time': '10:35:00', 'action': 'Incident created', 'actor': 'Security Analyst', 'type': 'creation', 'details': 'Incident INC-001 created'},
      {'time': '10:38:22', 'action': 'Assigned to responder', 'actor': 'John Doe', 'type': 'assignment', 'details': 'Assigned to Incident Response Team'},
      {'time': '10:45:00', 'action': 'Investigation started', 'actor': 'Jane Smith', 'type': 'investigation', 'details': 'Initial investigation initiated'},
      {'time': '11:00:00', 'action': 'Containment action', 'actor': 'System', 'type': 'containment', 'details': 'Source IP blocked at firewall'},
      {'time': '11:30:00', 'action': 'Evidence collected', 'actor': 'Forensic Team', 'type': 'evidence', 'details': 'Network logs and memory dump collected'},
      {'time': '12:00:00', 'action': 'Root cause identified', 'actor': 'Security Team', 'type': 'analysis', 'details': 'Malicious payload identified'},
      {'time': '13:00:00', 'action': 'Remediation completed', 'actor': 'System', 'type': 'remediation', 'details': 'Malware removed and patches applied'},
      {'time': '14:00:00', 'action': 'Incident resolved', 'actor': 'Security Manager', 'type': 'resolution', 'details': 'Incident marked as resolved'},
    ],
    'INC-002': [
      {'time': '09:15:00', 'action': 'User reported suspicious email', 'actor': 'User', 'type': 'report', 'details': 'Phishing email received'},
      {'time': '09:20:00', 'action': 'Email analyzed', 'actor': 'Security Team', 'type': 'analysis', 'details': 'Malicious links detected'},
      {'time': '09:30:00', 'action': 'Incident created', 'actor': 'Security Analyst', 'type': 'creation', 'details': 'Incident INC-002 created'},
      {'time': '09:45:00', 'action': 'Email quarantined', 'actor': 'System', 'type': 'containment', 'details': 'Malicious email removed from all mailboxes'},
      {'time': '10:00:00', 'action': 'User notified', 'actor': 'Security Team', 'type': 'notification', 'details': 'User informed of phishing attempt'},
      {'time': '10:30:00', 'action': 'Incident closed', 'actor': 'Security Analyst', 'type': 'closure', 'details': 'No further action required'},
    ],
  };

  List<Map<String, dynamic>> get _currentTimeline {
    return _timelines[_selectedIncident] ?? [];
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Incident Timeline'),
        actions: [
          DropdownButton<String>(
            value: _selectedIncident,
            items: const [
              DropdownMenuItem(value: 'INC-001', child: Text('INC-001 - Security Breach')),
              DropdownMenuItem(value: 'INC-002', child: Text('INC-002 - Phishing Attack')),
            ],
            onChanged: (value) {
              if (value != null) {
                setState(() => _selectedIncident = value);
              }
            },
            underline: const SizedBox(),
            style: const TextStyle(color: Colors.white),
          ),
          const SizedBox(width: 16),
        ],
      ),
      body: _currentTimeline.isEmpty
          ? const Center(child: Text('No timeline data available'))
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _currentTimeline.length,
              itemBuilder: (context, index) {
                final event = _currentTimeline[index];
                final isLast = index == _currentTimeline.length - 1;
                return _buildTimelineItem(event, isLast, index);
              },
            ),
    );
  }

  Widget _buildTimelineItem(Map<String, dynamic> event, bool isLast, int index) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Timeline line and dot
        SizedBox(
          width: 60,
          child: Column(
            children: [
              Container(
                width: 16,
                height: 16,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: _getEventColor(event['type']),
                  border: Border.all(color: Colors.white, width: 2),
                ),
              ),
              if (!isLast)
                Container(
                  width: 2,
                  height: 80,
                  color: Colors.grey.shade300,
                ),
            ],
          ),
        ),
        // Event content
        Expanded(
          child: Card(
            margin: const EdgeInsets.only(bottom: 16),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: _getEventColor(event['type']).withOpacity(0.1),
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: Text(
                          event['time'],
                          style: TextStyle(
                            color: _getEventColor(event['type']),
                            fontWeight: FontWeight.bold,
                            fontSize: 12,
                          ),
                        ),
                      ),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          event['action'],
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Text(
                    event['details'],
                    style: TextStyle(color: Colors.grey.shade600),
                  ),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      Icon(Icons.person_outline, size: 14, color: Colors.grey.shade500),
                      const SizedBox(width: 4),
                      Text(
                        event['actor'],
                        style: TextStyle(fontSize: 12, color: Colors.grey.shade500),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        ),
      ],
    );
  }

  Color _getEventColor(String type) {
    switch (type) {
      case 'detection':
      case 'alert':
        return Colors.red;
      case 'creation':
        return Colors.blue;
      case 'assignment':
        return Colors.purple;
      case 'investigation':
      case 'analysis':
        return Colors.orange;
      case 'containment':
        return Colors.yellow.shade700;
      case 'evidence':
        return Colors.brown;
      case 'remediation':
        return Colors.green;
      case 'resolution':
      case 'closure':
        return Colors.teal;
      case 'report':
        return Colors.pink;
      case 'notification':
        return Colors.cyan;
      default:
        return Colors.grey;
    }
  }
}