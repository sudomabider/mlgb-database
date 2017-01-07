import React from 'react'
import { connect, PromiseState } from 'react-refetch'
import PromiseStateContainer from './core/PromiseStateContainer'
import { LineChart } from './Chart'
import {Card, CardHeader, CardBlock} from 'reactstrap'
import recordHelper from '../utils/RecordHelper'

class PlayerView extends React.Component {

  render() {
    return (
      <PromiseStateContainer
        ps={PromiseState.all([this.props.playerFetch])}
        onFulfillment={([data]) => {
          const player = data.player;
          const records = data.records;
          return (
              <div className="text-xs-center">
                <h2>{player.name}</h2>
                <h4>
                  <img src={player.league.icon_tiny} className="img-responsive mr-1"/>
                  {player.league.name}
                </h4>
                <hr/>
                <h5>Career Stats: {player.meta.careerStart} - {player.meta.careerEnd}</h5>
                <div>
                  <ul className="list-unstyled">
                    <li>Highest Trophy Count: {player.meta.highTrophy}</li>
                    <li>Lowest Trophy Count: {player.meta.lowTrophy}</li>
                    <li>Highest Seasonal Donations: {player.meta.highDonation}</li>
                    <li>Highest Seasonal Received Donations: {player.meta.highDonationsReceived}</li>
                  </ul>
                </div>
                <hr/>
                <h5>Most recent record: {data.latest}</h5>
                <p>Showing {data.days} days of most recent data</p>

                <div className="row mt-2">
                  <div className="col-md-6">
                    <Card>
                      <CardHeader>Trophies</CardHeader>
                      <CardBlock>
                        <LineChart label="Trophies" color={player.color.value}
                                   labels={recordHelper.filterRecords(records, 'time', false)}
                                   data={recordHelper.filterRecords(records, 'trophies')}/>
                      </CardBlock>
                    </Card>
                  </div>
                  <div className="col-md-6">
                    <Card>
                      <CardHeader>Activity (Trophies Variation)</CardHeader>
                      <CardBlock>
                        <LineChart label="Variantion" color={player.color.value}
                                   labels={recordHelper.filterRecords(records, 'time', false)}
                                   data={recordHelper.filterRecords(records, 'trophiesVariation')}/>
                      </CardBlock>
                    </Card>
                  </div>
                  <div className="col-md-6">
                    <Card>
                      <CardHeader>Donations</CardHeader>
                      <CardBlock>
                        <LineChart label="Donations" color={player.color.value}
                                   labels={recordHelper.filterRecords(records, 'time', false)}
                                   data={recordHelper.filterRecords(records, 'donations')}/>
                      </CardBlock>
                    </Card>
                  </div>
                  <div className="col-md-6">
                    <Card>
                      <CardHeader>Donations Received</CardHeader>
                      <CardBlock>
                        <LineChart label="Received" color={player.color.value}
                                   labels={recordHelper.filterRecords(records, 'time', false)}
                                   data={recordHelper.filterRecords(records, 'donationsReceived')}/>
                      </CardBlock>
                    </Card>
                  </div>
                </div>
              </div>
          )
        }}
      />
    )
  }

}

export default connect(props => ({
  playerFetch: `/api/players/${props.params.id}`,
}))(PlayerView)
