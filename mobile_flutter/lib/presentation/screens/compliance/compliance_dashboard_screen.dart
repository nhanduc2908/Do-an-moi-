import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/compliance_provider.dart';
import '../../widgets/dashboard/statistic_card.dart';

class ComplianceDashboardScreen extends ConsumerStatefulWidget {
  const ComplianceDashboardScreen({super.key});

  @override
  ConsumerState<ComplianceDashboardScreen> createState() => _ComplianceDashboardScreenState();
}

class _ComplianceDashboardScreenState extends ConsumerState<ComplianceDashboardScreen> {
  @override
  void initState() {
    super.initState();
    ref.read(complianceProvider.notifier).loadDashboard();
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(complianceProvider);
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('Compliance Dashboard'),
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  GridView.count(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    crossAxisCount: 2,
                    mainAxisSpacing: 16,
                    crossAxisSpacing: 16,
                    childAspectRatio: 1.5,
                    children: [
                      StatisticCard(
                        title: 'ISO 27001',
                        value: '85%',
                        icon: Icons.security,
                        color: Colors.blue,
                      ),
                      StatisticCard(
                        title: 'GDPR',
                        value: '78%',
                        icon: Icons.privacy_tip,
                        color: Colors.green,
                      ),
                      StatisticCard(
                        title: 'PCI DSS',
                        value: '72%',
                        icon: Icons.credit_card,
                        color: Colors.orange,
                      ),
                      StatisticCard(
                        title: 'HIPAA',
                        value: '90%',
                        icon: Icons.health_and_safety,
                        color: Colors.purple,
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Recent Audit Findings', style: TextStyle(fontWeight: FontWeight.bold)),
                          const SizedBox(height: 8),
                          _buildFinding('ISO 27001 A.9 - Access Control Review', 'In Progress', Colors.orange),
                          _buildFinding('GDPR Article 30 - Records of Processing', 'Compliant', Colors.green),
                          _buildFinding('PCI DSS 3.2 - Protect Stored Cardholder Data', 'Non-Compliant', Colors.red),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
    );
  }

  Widget _buildFinding(String text, String status, Color color) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Container(
            width: 8,
            height: 8,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: color,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(child: Text(text)),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
            decoration: BoxDecoration(
              color: color.withOpacity(0.2),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Text(status, style: TextStyle(color: color, fontSize: 12)),
          ),
        ],
      ),
    );
  }
}