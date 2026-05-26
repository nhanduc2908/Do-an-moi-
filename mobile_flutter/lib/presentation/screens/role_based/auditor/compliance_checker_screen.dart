import 'package:flutter/material.dart';

class ComplianceCheckerScreen extends StatefulWidget {
  const ComplianceCheckerScreen({super.key});

  @override
  State<ComplianceCheckerScreen> createState() => _ComplianceCheckerScreenState();
}

class _ComplianceCheckerScreenState extends State<ComplianceCheckerScreen> {
  String _selectedStandard = 'ISO 27001';
  
  final Map<String, Map<String, dynamic>> _complianceData = {
    'ISO 27001': {
      'score': 85,
      'controls': [
        {'name': 'Access Control', 'status': 'Compliant', 'score': 90},
        {'name': 'Cryptography', 'status': 'Partial', 'score': 70},
        {'name': 'Physical Security', 'status': 'Compliant', 'score': 95},
        {'name': 'Incident Response', 'status': 'Non-Compliant', 'score': 45},
      ],
    },
    'GDPR': {
      'score': 78,
      'controls': [
        {'name': 'Data Protection', 'status': 'Compliant', 'score': 85},
        {'name': 'User Rights', 'status': 'Partial', 'score': 65},
        {'name': 'Breach Notification', 'status': 'Compliant', 'score': 90},
        {'name': 'Data Transfer', 'status': 'Non-Compliant', 'score': 40},
      ],
    },
    'PCI DSS': {
      'score': 72,
      'controls': [
        {'name': 'Network Security', 'status': 'Compliant', 'score': 80},
        {'name': 'Cardholder Data', 'status': 'Partial', 'score': 60},
        {'name': 'Access Control', 'status': 'Compliant', 'score': 85},
        {'name': 'Monitoring', 'status': 'Non-Compliant', 'score': 50},
      ],
    },
  };

  @override
  Widget build(BuildContext context) {
    final data = _complianceData[_selectedStandard]!;
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('Compliance Checker'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () {},
          ),
          IconButton(
            icon: const Icon(Icons.download),
            onPressed: () {},
          ),
        ],
      ),
      body: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.grey.shade100,
            child: Row(
              children: [
                const Text('Standard: ', style: TextStyle(fontWeight: FontWeight.bold)),
                const SizedBox(width: 12),
                Expanded(
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(8),
                      border: Border.all(color: Colors.grey.shade300),
                    ),
                    child: DropdownButtonHideUnderline(
                      child: DropdownButton<String>(
                        value: _selectedStandard,
                        isExpanded: true,
                        items: const [
                          DropdownMenuItem(value: 'ISO 27001', child: Text('ISO 27001')),
                          DropdownMenuItem(value: 'GDPR', child: Text('GDPR')),
                          DropdownMenuItem(value: 'PCI DSS', child: Text('PCI DSS')),
                        ],
                        onChanged: (value) => setState(() => _selectedStandard = value!),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
          Expanded(
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(20),
                    child: Column(
                      children: [
                        const Text('Overall Compliance Score', style: TextStyle(fontWeight: FontWeight.bold)),
                        const SizedBox(height: 16),
                        Stack(
                          alignment: Alignment.center,
                          children: [
                            SizedBox(
                              height: 150,
                              width: 150,
                              child: CircularProgressIndicator(
                                value: data['score'] / 100,
                                strokeWidth: 12,
                                backgroundColor: Colors.grey.shade200,
                                valueColor: const AlwaysStoppedAnimation<Color>(Colors.green),
                              ),
                            ),
                            Column(
                              children: [
                                Text(
                                  '${data['score']}%',
                                  style: const TextStyle(fontSize: 32, fontWeight: FontWeight.bold),
                                ),
                                const Text('Compliant'),
                              ],
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
                const SizedBox(height: 16),
                const Text('Control Status', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                const SizedBox(height: 8),
                ...data['controls'].map((control) => Card(
                  margin: const EdgeInsets.only(bottom: 8),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: _getStatusColor(control['status']),
                      child: Icon(_getStatusIcon(control['status']), color: Colors.white),
                    ),
                    title: Text(control['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Text('Score: ${control['score']}%'),
                    trailing: Chip(
                      label: Text(control['status']),
                      backgroundColor: _getStatusColor(control['status']).withOpacity(0.2),
                    ),
                    onTap: () {},
                  ),
                )),
                const SizedBox(height: 16),
                ElevatedButton(
                  onPressed: () {},
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.blue,
                    padding: const EdgeInsets.symmetric(vertical: 16),
                  ),
                  child: const Text('Run Full Compliance Check'),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Compliant': return Colors.green;
      case 'Partial': return Colors.orange;
      case 'Non-Compliant': return Colors.red;
      default: return Colors.grey;
    }
  }

  IconData _getStatusIcon(String status) {
    switch (status) {
      case 'Compliant': return Icons.check_circle;
      case 'Partial': return Icons.warning;
      case 'Non-Compliant': return Icons.error;
      default: return Icons.help;
    }
  }
}